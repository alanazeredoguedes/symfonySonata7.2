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

namespace App\Application\Sonata\MediaBundle\Admin;

use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\Filter\DateTimeType;
use Sonata\AdminBundle\Form\Type\ModelListType;
use Sonata\DoctrineORMAdminBundle\Filter\ChoiceFilter;
use Sonata\MediaBundle\Form\DataTransformer\ProviderDataTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

/**
 * Overload Sonata Media Admin
 */
class MediaAdmin extends \Sonata\MediaBundle\Admin\ORM\MediaAdmin
{

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $options = [
            'choices' => [],
        ];

        foreach ($this->pool->getContexts() as $name => $context) {
            $options['choices'][$name] = $name;
        }

        $datagridMapper
            ->add('name')
            ->add('providerReference')
            ->add('enabled')
            ->add('context', null, [
                'show_filter' => true !== $this->getPersistentParameter('hide_context'),
            ], ChoiceType::class, $options);

        if (null !== $this->categoryManager) {
            $datagridMapper->add('category', null, ['show_filter' => false]);
        }

        $datagridMapper
            ->add('width',null,[
                'label' => 'Width',
            ])
            ->add('height',null,[
                'label' => 'Height',
            ])
            ->add('contentType','doctrine_orm_choice', [
                'label' => 'Tipo de Conteudo',
                'global_search' => true,
                'field_type' => ChoiceType::class,
                'field_options' => [
                    'choices' => [
                        'image/jpeg' => 'image/jpeg',
                        'image/jpg' => 'image/jpg',
                        'image/png' => 'image/png',
                        'image/x-png' => 'image/x-png',
                        'application/pdf' => 'application/pdf',
                        'application/x-pdf' => 'application/x-pdf',
                        'application/rtf' => 'application/rtf',
                        'text/html' => 'text/html',
                        'text/rtf' => 'text/rtf',
                        'text/plain' => 'text/plain',
                    ],                'multiple' => true,


                ],
                'required' => false
            ])
        ;

        $providers = [];

        $providerNames = (array) $this->pool->getProviderNamesByContext($this->getPersistentParameter('context', $this->pool->getDefaultContext()));
        foreach ($providerNames as $name) {
            $providers[$name] = $name;
        }

        $datagridMapper->add('providerName', ChoiceFilter::class, [
            'label' => 'Nome do Provedor',
            'field_options' => [
                'choices' => $providers,
                'required' => false,
                'multiple' => false,
                'expanded' => false,
            ],
            'field_type' => ChoiceType::class,
        ]);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $this->setListMode('list');
        unset($this->listModes['mosaic']);

        $listMapper
            ->addIdentifier('name',null,[
                'label' => 'nome',
            ])



        ;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $media = $this->getSubject();

        if (!$media) {
            $media = $this->getNewInstance();
        }

        if (!$media || !$media->getProviderName()) {
            return;
        }

        $formMapper->add('providerName', HiddenType::class);

        $formMapper->getFormBuilder()->addModelTransformer(new ProviderDataTransformer($this->pool, $this->getClass()), true);

        $provider = $this->pool->getProvider($media->getProviderName());

        if ($media->getId()) {
            $provider->buildEditForm($formMapper);
        } else {
            $provider->buildCreateForm($formMapper);
        }

        if (null !== $this->categoryManager) {
            $formMapper->add('category', ModelListType::class, [], [
                'link_parameters' => [
                    'context' => $media->getContext(),
                    'hide_context' => true,
                    'mode' => 'tree',
                ],
            ]);
        }
    }


}
