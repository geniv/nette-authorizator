<?php

namespace Authorizator\Drivers;

use Authorizator\Authorizator;


/**
 * Class ArrayDriver
 *
 * @author  geniv
 * @package Authorizator\Drivers
 */
class ArrayDriver extends Authorizator
{

    /**
     * Init data.
     *
     * @param array $parameters
     */
    protected function init(array $parameters)
    {
        if ($this->policy != self::POLICY_NONE) {
            // set role
            foreach ($parameters['role'] as $role) {
                $this->permission->addRole($role);

                $this->role[$role] = ['id' => $role, 'role' => $role];
            }

            // set resource
            foreach ($parameters['resource'] as $resource) {
                $this->permission->addResource($resource);

                $this->resource[$resource] = ['id' => $resource, 'resource' => $resource];
            }

            // set privilege
            foreach ($parameters['privilege'] as $privilege) {
                $this->privilege[$privilege] = ['id' => $privilege, 'privilege' => $privilege];
            }

            // for deny enable all
            if ($this->policy == self::POLICY_DENY) {
                $this->permission->allow();
            }

            // set acl
            foreach ($parameters['acl'] as $role => $resources) {
                if (is_array($resources)) {
                    foreach ($resources as $resource => $privilege) {
                        // fill acl array
                        foreach ($privilege as $item) {
                            $this->acl[] = ['id_role' => $role, 'id_resource' => $resource, 'id_privilege' => $item];
                        }

                        // automtic remove acl not exist role from NEON file
                        if (!isset($this->role[$role])) {
                            $this->saveAcl($role, []);
                        }

                        // convert acl all to permission all
                        if (in_array('all', $privilege)) {
                            $privilege = self::ALL;
                        }

                        $this->setAllowed($role, $resource, $privilege);
                    }
                } else {
                    //vse
                    if ($resources == 'all') {

                        $this->acl[] = ['id_role' => $role, 'id_resource' => null, 'id_privilege' => null];

                        $this->setAllowed($role);
                    }
                }
            }
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
        return 0;
    }


    /**
     * Save resource.
     *
     * @param array $values
     * @return int
     */
    public function saveResource(array $values)
    {
        return 0;
    }


    /**
     * Save privilege.
     *
     * @param array $values
     * @return int
     */
    public function savePrivilege(array $values)
    {
        return 0;
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
        return 0;
    }
}
