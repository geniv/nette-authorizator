<?php

namespace Authorizator\Drivers;

use Nette\Neon\Neon;


/**
 * Class NeonDriver
 *
 * @author  geniv
 * @package Authorizator\Drivers
 */
class NeonDriver extends ArrayDriver
{
    private $path = null;
    private $data = null;


    /**
     * NeonDriver constructor.
     *
     * @param array $parameters
     */
    public function __construct(array $parameters)
    {
        $this->setPath($parameters['path']);

        parent::__construct($parameters);
    }


    /**
     * Set path.
     *
     * @param $path
     * @return $this
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }


    /**
     * Init data.
     *
     * @param array $parameters
     */
    protected function init(array $parameters)
    {
        if ($this->path && file_exists($this->path)) {
            $this->data = Neon::decode(file_get_contents($this->path));

            parent::init(array_merge($parameters, $this->data));
        }
    }


    /**
     * Save role.
     *
     * @param array $values
     * @return int
     */
    public function saveRole(array $values)
    {
        $id = $values['id'];
        unset($values['id']);

        if (!$id) {
            // add
            $this->data['role'][] = $values['role'];
        } else {
            // update
            if ($values) {
                $index = array_search($id, $this->data['role']);
                if ($index !== false) {
                    $this->data['role'][$index] = $values['role'];
                }
            } else {
                // delete
                $index = array_search($id, $this->data['role']);
                if ($index !== false) {
                    unset($this->data['role'][$index]);
                    $this->data['role'] = array_values($this->data['role']);    // correct fix for index array
                }
            }
        }
        return file_put_contents($this->path, Neon::encode($this->data, Neon::BLOCK));
    }


    /**
     * Save resource.
     *
     * @param array $values
     * @return int
     */
    public function saveResource(array $values)
    {
        $id = $values['id'];
        unset($values['id']);

        if (!$id) {
            // add
            $this->data['resource'][] = $values['resource'];
        } else {
            // update
            if ($values) {
                $index = array_search($id, $this->data['resource']);
                if ($index !== false) {
                    $this->data['resource'][$index] = $values['resource'];
                }
            } else {
                // delete
                $index = array_search($id, $this->data['resource']);
                if ($index !== false) {
                    unset($this->data['resource'][$index]);
                    $this->data['resource'] = array_values($this->data['resource']);    // correct fix for index array
                }
            }
        }
        return file_put_contents($this->path, Neon::encode($this->data, Neon::BLOCK));
    }


    /**
     * Save privilege.
     *
     * @param array $values
     * @return int
     */
    public function savePrivilege(array $values)
    {
        $id = $values['id'];
        unset($values['id']);

        if (!$id) {
            // add
            $this->data['privilege'][] = $values['privilege'];
        } else {
            // update
            if ($values) {
                $index = array_search($id, $this->data['privilege']);
                if ($index !== false) {
                    $this->data['privilege'][$index] = $values['privilege'];
                }
            } else {
                // delete
                $index = array_search($id, $this->data['privilege']);
                if ($index !== false) {
                    unset($this->data['privilege'][$index]);
                    $this->data['privilege'] = array_values($this->data['privilege']);    // correct fix for index array
                }
            }
        }
        return file_put_contents($this->path, Neon::encode($this->data, Neon::BLOCK));
    }


    /**
     * Save acl.
     *
     * @param       $role
     * @param array $values
     * @return int
     */
    public function saveAcl($role, array $values)
    {
        unset($this->data['acl'][$role]);

        // save all to role
        if (isset($values['all']) && $values['all']) {
            $this->data['acl'][$role] = 'all';
            return file_put_contents($this->path, Neon::encode($this->data, Neon::BLOCK));
        }

        // save acl by role && resource
        foreach ($values as $idResource => $item) {
            if ($item && is_array($item)) {
                if (!in_array('all', $item)) {
                    $this->data['acl'][$role][$idResource] = $item;
                } else {
                    $this->data['acl'][$role][$idResource][] = 'all';
                }
            }
        }
        return file_put_contents($this->path, Neon::encode($this->data, Neon::BLOCK));
    }
}
