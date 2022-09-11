<?php

namespace App\Application\Sonata\UserBundle\Admin;

use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\UserBundle\Form\Type\SecurityRolesType;

use Sonata\UserBundle\Admin\Model\GroupAdmin as BaseGroupAdmin;

class GroupAdmin extends BaseGroupAdmin
{
    /**
     * {@inheritdoc}
     */
    protected $formOptions = [
        'validation_groups' => 'Registration',
    ];

    /**
     * {@inheritdoc}
     */
    public function getNewInstance()
    {
        $class = $this->getClass();

        return new $class('', []);
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper): void
    {
        //$this->setListMode('list');
        unset($this->listModes['mosaic']);

        $listMapper
            ->addIdentifier('name',null,[
                'label' => 'Nome'
            ])
            //->add('roles')
            ->add('_action', null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ],
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('name',null,[
                'label' => 'Nome'
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper

            ->tab('Grupo')
                ->with('General', ['class' => 'col-md-12'])
                    ->add('name', null,[
                        'label' => 'Nome: '
                    ])
                ->end()
            ->end()

            ->tab('Security')
                ->with('Módulos e Permissões', ['class' => 'col-md-12 div-roles'])

                    ->add('roles', SecurityRolesType::class, [
                        'label' => ' ',
                        'expanded' => false,
                        'multiple' => true,
                        'required' => false,
                        'attr' => [
                            'class' => 'div-select-roles',
                            //'style' => 'display:none;'
                        ],
                        // $("[value='ROLE_USER']").prop("selected", "selected");
                        // $("[value='ROLE_USER']").removeAttr('selected');
                    ])

                ->end()
            ->end()

        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureShowFields(ShowMapper $showMapper): void
    {
        $showMapper
            ->add('name',null,[
                'label' => 'Nome: '
            ])

            ->add('roles', null,[
                'label' => 'Módulos e Permissões: '
            ])
        ;
    }


}
