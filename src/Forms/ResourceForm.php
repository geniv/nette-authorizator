<?php

namespace Authorizator\Forms;

use Authorizator\Authorizator;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Localization\ITranslator;


/**
 * Class ResourceForm
 *
 * @author  geniv
 * @package Authorizator\Forms
 */
class ResourceForm extends Control
{
    /** @var Authorizator */
    private $authorizator;
    /** @var ITranslator|null */
    private $translator;
    /** @var string template path */
    private $templatePath;

    private $state = null;
    /** @var callback method */
    public $onSuccess, $onError;


    /**
     * Forms constructor.
     *
     * @param Authorizator     $authorizator
     * @param ITranslator|null $translator
     */
    public function __construct(Authorizator $authorizator, ITranslator $translator = null)
    {
        parent::__construct();

        $this->authorizator = $authorizator;
        $this->translator = $translator;

        $this->templatePath = __DIR__ . '/ResourceForm.latte';  // default path
    }


    /**
     * Set template path.
     *
     * @param $path
     * @return $this
     */
    public function setTemplatePath($path)
    {
        $this->templatePath = $path;
        return $this;
    }


    /**
     * Create component form.
     *
     * @param $name
     * @return Form
     */
    protected function createComponentForm($name)
    {
        $form = new Form($this, $name);
        $form->setTranslator($this->translator);

        $form->addHidden('id');
        $form->addText('resource', 'acl-resourceform-resource')
            ->setRequired('acl-resourceform-role-required');

        $form->addSubmit('save', 'acl-resourceform-save');

        $form->onSuccess[] = function ($form, array $values) {
            if ($this->authorizator->saveResource($values)) {
                $this->onSuccess($values);
            } else {
                $this->onError($values);
            }
        };
        return $form;
    }


    /**
     * Handle add.
     */
    public function handleAdd()
    {
        $this->state = 'add';
    }


    /**
     * Handle update.
     *
     * @param $id
     */
    public function handleUpdate($id)
    {
        $this->state = 'update';

        $resource = $this->authorizator->getResource();
        if (isset($resource[$id])) {
            $this['form']->setDefaults($resource[$id]);
        }
    }


    /**
     * Handle delete.
     *
     * @param $id
     */
    public function handleDelete($id)
    {
        $resource = $this->authorizator->getResource();
        if (isset($resource[$id])) {
            $values = $resource[$id];

            if ($this->authorizator->saveResource(['id' => $id])) {
                $this->onSuccess($values);
            } else {
                $this->onError($values);
            }
        }
    }


    /**
     * Render resource.
     */
    public function render()
    {
        $template = $this->getTemplate();

        $template->state = $this->state;
        $template->resource = $this->authorizator->getResource();

        $template->setFile($this->templatePath);
        $template->render();
    }
}
