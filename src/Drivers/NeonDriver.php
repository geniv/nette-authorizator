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
    /** @var string */
    private $path = null;
    /** @var array */
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
     * General save.
     *
     * @param array  $values
     * @param string $dataIndex
     * @return int
     * @throws UniqueConstraintViolationException
     */
    private function generalSave(array $values, $dataIndex)
    {
        $id = $values['id'];
        unset($values['id']);

        if (!$id) {
            // add
            if (!in_array($values[$dataIndex], $this->data[$dataIndex])) {
                $this->data[$dataIndex][] = $values[$dataIndex];
            } else {
                throw new UniqueConstraintViolationException($dataIndex . ' already exist!');
            }
        } else {
            // update
            if ($values) {
                if ($id != $values[$dataIndex]) {
                    $index = array_search($id, $this->data[$dataIndex]);
                    if ($index !== false && !in_array($values[$dataIndex], $this->data[$dataIndex])) {
                        $this->data[$dataIndex][$index] = $values[$dataIndex];
                    } else {
                        throw new UniqueConstraintViolationException($dataIndex . ' already exist!');
                    }
                } else {
                    return 0;
                }
            } else {
                // delete
                $index = array_search($id, $this->data[$dataIndex]);
                if ($index !== false) {
                    unset($this->data[$dataIndex][$index]);
                    $this->data[$dataIndex] = array_values($this->data[$dataIndex]);    // correct fix for index array
                }
            }
        }
        return file_put_contents($this->path, Neon::encode($this->data, Neon::BLOCK));
    }


    /**
     * Save role.
     *
     * @param array $values
     * @return int
     */
    public function saveRole(array $values)
    {
        return $this->generalSave($values, 'role');
    }


    /**
     * Save resource.
     *
     * @param array $values
     * @return int
     */
    public function saveResource(array $values)
    {
        return $this->generalSave($values, 'resource');
    }


    /**
     * Save privilege.
     *
     * @param array $values
     * @return int
     */
    public function savePrivilege(array $values)
    {
        return $this->generalSave($values, 'privilege');
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
