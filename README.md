ACL
===

Installation
------------

```sh
$ composer require geniv/nette-authorizator
```
or
```json
"geniv/nette-authorizator": ">=1.0.0"
```

require:
```json
"php": ">=5.6.0",
"nette/nette": ">=2.4.0",
"dibi/dibi": ">=3.0.0"
```

Include in application
----------------------

### available source drivers:
- Neon (neon filesystem)
- Dibi (dibi + cache)
- Array (neon configure)

### policy:
- `allow` - all is deny, allow part
- `deny` - all is allow, deny part
- `none` - all is allow, ignore part

neon configure:
```neon
# acl
authorizator:
#   autowired: false    # default null, false => disable autowiring (in case multiple linked extension) | self
	policy: allow		# allow (all is deny, allow part) | deny (all is allow, deny part) | none (all is allow, ignore part)
	source: "Neon"
	path: %appDir%/components/test/nette-authorizator/sql/acl.neon
#	source: "Dibi"
#	tablePrefix: %tablePrefix%
#	source: "Array"
#	role:
#		guest: "Návštěvník"
#		moderator: "Moderátor"
#		admin: "Adminstrator"
#	resource:
#		article: "članky"
#		comment: "komenáře"
#		poll: "hlasování"
#	privilege:
#		show: "zobrazit"
#		insert: "vložit"
#		update: "upravit"
#		delete: "smazat"
#	acl:
#		moderator:
#			article: [show, insert, update]
#		admin: all
```

neon configure extension:
```neon
extensions:
    authorizator: Authorizator\Bridges\Nette\Extension
```

presenters:
```php
$acl = $this->user->getAuthorizator();
$acl->isAllowed('guest', 'sekce-forum', 'zobrazit');

$this->user->isAllowed('sekce-forum', 'zobrazit');
```

usage:
```latte
$user->isAllowed('sekce-forum', 'zobrazit')
```

presenters form:
```php
protected function createComponentRoleForm(RoleForm $roleForm): RoleForm
{
    //$roleForm->setTemplatePath(path);
    //$roleForm->onSuccess[] = function (array $values) { };
    //$roleForm->onError[] = function (array $values) { };
    return $roleForm;
}


protected function createComponentResourceForm(ResourceForm $resourceForm): ResourceForm
{
    //$resourceForm->setTemplatePath(path);
    //$resourceForm->onSuccess[] = function (array $values) { };
    //$resourceForm->onError[] = function (array $values) { };
    return $resourceForm;
}


protected function createComponentPrivilegeForm(PrivilegeForm $privilegeForm): PrivilegeForm
{
    //$privilegeForm->setTemplatePath(path);
    //$privilegeForm->onSuccess[] = function (array $values) { };
    //$privilegeForm->onError[] = function (array $values) { };
    return $privilegeForm;
}
```

usage form:
```latte
{control acl:role}
{control acl:resource}
{control acl:privilege}
{control acl}
```
