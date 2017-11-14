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
- Neon (neon filesystem) - support **form**
- Dibi (dibi + cache) - support **form**
- Array (neon configure)

### policy:
- `allow` - all is deny, allow part
- `deny` - all is allow, deny part
- `none` - all is allow, ignore part

neon configure:
```neon
# acl
authorizator:
#   autowired: false    # default null, true|false|self|null
    policy: allow       # allow (all is deny, allow part) | deny (all is allow, deny part) | none (all is allow, ignore part)
    source: "Neon"
    path: %appDir%/components/test/nette-authorizator/sql/acl.neon
#    source: "Dibi"
#    tablePrefix: %tablePrefix%
#    source: "Array"
#    role:
#        - guest
#        - moderator
#        - admin
#    resource:
#        - article
#        - comment
#        - poll
#    privilege:
#        - show
#        - insert
#        - update
#        - delete
#    acl:
#        moderator:
#            article: [show, insert, update]
#        admin: all
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
<span n:if="$user->isAllowed('sekce-forum', 'zobrazit')">...</span>
```

**All method onSuccess callback are default defined like `$this->redirect('this');`**

presenters form:
```php
use Authorizator\Forms\AclForm;
use Authorizator\Forms\PrivilegeForm;
use Authorizator\Forms\ResourceForm;
use Authorizator\Forms\RoleForm;
...
abstract class BasePresenter extends Presenter
{
    use AutowiredComponent;
...

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

protected function createComponentAclForm(AclForm $aclForm): AclForm
{
    //$privilegeForm->onSuccess[] = function (array $values) { };
    //$privilegeForm->onError[] = function (array $values) { };
    return $aclForm;
}
```

generic usage on security base presenter:
```php
$acl = $this->user->getAuthorizator();
// manual set allowed with internal resolve policy
$acl->setAllowed(IAuthorizator::ALL, 'Homepage');
$acl->setAllowed(IAuthorizator::ALL, 'Login');

if (!$this->user->isAllowed($this->name, $this->action)) {
    // NOT ALLOWED
}
```

form not required for correct function ACL.

Available form: role, resource, privilege and acl.

usage **form**:
```latte
{control roleForm}
{control resourceForm}
{control privilegeForm}
{control aclForm}
```
