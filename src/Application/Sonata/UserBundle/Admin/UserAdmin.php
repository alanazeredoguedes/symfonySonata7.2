<?php

declare(strict_types=1);

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Application\Sonata\UserBundle\Admin;

use App\Application\Internit\LeadBundle\Entity\Lead;
use FOS\UserBundle\Model\UserManagerInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\Form\Type\DatePickerType;
use Sonata\UserBundle\Form\Type\SecurityRolesType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\LocaleType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimezoneType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;

use Sonata\UserBundle\Admin\Model\UserAdmin as BaseUserAdmin;

class UserAdmin extends BaseUserAdmin
{
    /**
     * @var UserManagerInterface
     */
    protected $userManager;

    /**
     * {@inheritdoc}
     */
    public function getFormBuilder()
    {
        $this->formOptions['data_class'] = $this->getClass();

        $options = $this->formOptions;
        $options['validation_groups'] = ['Default', 'Profile'];

        if (!$this->getSubject() || null === $this->getSubject()->getId()) {
            $options['validation_groups'] = ['Default', 'Registration'];
        }

        $formBuilder = $this->getFormContractor()->getFormBuilder($this->getUniqid(), $options);

        $this->defineFormBuilder($formBuilder);

        return $formBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function preUpdate($user): void
    {
        $this->getUserManager()->updateCanonicalFields($user);
        $this->getUserManager()->updatePassword($user);
    }

    public function setUserManager(UserManagerInterface $userManager): void
    {
        $this->userManager = $userManager;
    }

    /**
     * @return UserManagerInterface
     */
    public function getUserManager()
    {
        return $this->userManager;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper): void
    {
        //$this->setListMode('list');
        unset($this->listModes['mosaic']);

        $listMapper
            ->addIdentifier('username')
            ->add('email')
            ->add('groups')
            ->add('enabled', null, ['editable' => true])
            ->add('createdAt')
        ;

        if ($this->isGranted('ROLE_ALLOWED_TO_SWITCH')) {
            $listMapper
                ->add('impersonating', 'string', ['template' => '@SonataUser/Admin/Field/impersonating.html.twig'])
            ;
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $filterMapper): void
    {
        $filterMapper
            ->add('id')
            ->add('username')
            ->add('email')
            ->add('groups')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureShowFields(ShowMapper $showMapper): void
    {
        $showMapper

            ->with('General')
                ->add('username',null,['label'=> 'Usuario'])
                ->add('email',null,['label'=> 'Email'])
            ->end()

            ->with('Profile')
                //->add('dateOfBirth')
                ->add('firstname',null,['label'=> 'Nome'])
                ->add('lastname',null,['label'=> 'Sobrenome'])
                //->add('website')
                //->add('biography')
                //->add('gender',null,['label'=> 'Genero'])
                //->add('locale')
                //->add('timezone')
                ->add('phone',null,['label'=> 'Telefone'])
            ->end()

            ->with('Groups')
                ->add('groups')
            ->end()

            /*->with('Social')
                ->add('facebookUid')
                ->add('facebookName')
                ->add('twitterUid')
                ->add('twitterName')
                ->add('gplusUid')
                ->add('gplusName')
            ->end()*/

            /*->with('Security')
                ->add('token')
                ->add('twoStepVerificationCode')
            ->end()*/
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper): void
    {
        // define group zoning
        $formMapper

            ->tab('User')
                ->with('Profile', ['class' => 'col-md-6'])->end()
                ->with('Acesso', ['class' => 'col-md-6'])->end()
                //->with('Social', ['class' => 'col-md-6'])->end()
            ->end()

            ->tab('Security')
                ->with('Status', ['class' => 'col-md-6'])->end()
                ->with('Groups', ['class' => 'col-md-6'])->end()
                //->with('Keys', ['class' => 'col-md-4'])->end()
                ->with('Módulos e Permissões', ['class' => 'col-md-12 div-roles'])->end()
            ->end()
        ;

        $now = new \DateTime();

        $genderOptions = [
            'label' => 'Genero',
            'choices' => \call_user_func([$this->getUserManager()->getClass(), 'getGenderList']),
            'required' => true,
            'translation_domain' => $this->getTranslationDomain(),
        ];

        $formMapper
            ->tab('User')

                ->with('Acesso')
                    ->add('username', null, ['label' => 'Usuario'])
                    ->add('email', null, ['label' => 'Email'])
                    ->add('plainPassword', TextType::class, [
                        'label' => 'Senha',
                        'required' => (!$this->getSubject() || null === $this->getSubject()->getId()),
                    ])
                ->end()

                ->with('Profile')

                    /*->add('dateOfBirth', DatePickerType::class, [
                        'years' => range(1900, $now->format('Y')),
                        'dp_min_date' => '1-1-1900',
                        'dp_max_date' => $now->format('c'),
                        'required' => false,
                    ])*/
                    ->add('firstname', null, [ 'label' => 'Nome', 'required' => true ])
                    ->add('lastname', null, ['label' => 'Sobrenome', 'required' => true])
                    //->add('website', UrlType::class, ['required' => false])
                    //->add('biography', TextType::class, ['required' => false])
                    //->add('gender', ChoiceType::class, $genderOptions)
                    //->add('timezone', TimezoneType::class, ['required' => false])
                    ->add('phone', IntegerType::class, ['label' => 'Telefone', 'required' => false])
                    //->add('locale', LocaleType::class, ['label' => 'Localização' ,'required' => false])

                ->end()

                /*->with('Social')
                    ->add('facebookUid', null, ['required' => false])
                    ->add('facebookName', null, ['required' => false])
                    ->add('twitterUid', null, ['required' => false])
                    ->add('twitterName', null, ['required' => false])
                    ->add('gplusUid', null, ['required' => false])
                    ->add('gplusName', null, ['required' => false])
                ->end()*/

            ->end()

            ->tab('Security')

                ->with('Status')
                    ->add('enabled', null, ['required' => false])
                ->end()

                ->with('Groups')
                    ->add('groups', ModelType::class, [
                        'required' => false,
                        'expanded' => true,
                        'multiple' => true,
                        'btn_add' => false,
                    ])
                ->end()

                ->with('Módulos e Permissões')
                    ->add('realRoles', SecurityRolesType::class, [
                        'label' => 'form.label_roles',
                        'expanded' => false,
                        'multiple' => true,
                        'required' => false,
                        'attr' => [
                            'class' => 'div-select-roles',
                            //'style' => 'display:none;'
                        ],
                    ])
                ->end()


                /*->with('Keys')
                    ->add('token', null, ['required' => false])
                    ->add('twoStepVerificationCode', null, ['required' => false])
                ->end()*/


            ->end()
        ;
    }

    protected function configureExportFields(): array
    {
        // Avoid sensitive properties to be exported.
        return array_filter(parent::configureExportFields(), static function (string $v): bool {
            return !\in_array($v, ['password', 'salt'], true);
        });
    }
}
