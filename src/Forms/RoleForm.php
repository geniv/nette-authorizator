<?php

namespace Authorizator\Forms;

use Authorizator\Drivers\UniqueConstraintViolationException;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Localization\ITranslator;
use Nette\Security\IAuthorizator;


/**
 * Class RoleForm
 *
 * @author  geniv
 * @package Authorizator\Forms
 */
class RoleForm extends Control
{
    /** @var IAuthorizator */
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
     * @param IAuthorizator    $authorizator
     * @param ITranslator|null $translator
     */
    public function __construct(IAuthorizator $authorizator, ITranslator $translator = null)
    {
        parent::__construct();

        $this->authorizator = $authorizator;
        $this->translator = $translator;

        $this->templatePath = __DIR__ . '/RoleForm.latte';  // default path
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
        $form->addText('role', 'acl-roleform-role')
            ->setRequired('acl-roleform-role-required');

        $form->addSubmit('save', 'acl-roleform-save');

        $form->onSuccess[] = function ($form, array $values) {
            try {
                if ($this->authorizator->saveRole($values)) {
                    $this->onSuccess($values);
                }
            } catch (UniqueConstraintViolationException $e) {
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

        $role = $this->authorizator->getRole();
        if (isset($role[$id])) {
            $this['form']->setDefaults($role[$id]);
        }
    }


    /**
     * Handle delete.
     *
     * @param $id
     */
    public function handleDelete($id)
    {
        $role = $this->authorizator->getRole();
        if (isset($role[$id])) {
            $values = $role[$id];

            if ($this->authorizator->saveRole(['id' => $id])) {
                $this->onSuccess($values);
            } else {
                $this->onError($values);
            }
        }
    }


    /**
     * Render role.
     */
    public function render()
    {
        $template = $this->getTemplate();

        $template->state = $this->state;
        $template->role = $this->authorizator->getRole();

        $template->setTranslator($this->translator);
        $template->setFile($this->templatePath);
        $template->render();
    }
}
