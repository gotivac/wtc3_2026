<?php 
class WebUser extends CWebUser
{
    public $location;
    /**
     * Overrides a Yii method that is used for roles in controllers (accessRules).
     *
     * @param string $operation Name of the operation required (here, a role).
     * @param mixed $params (opt) Parameters for this operation, usually the object to access.
     * @return bool Permission granted?
     */
    public function checkAccess($operation, $params=array(), $allowCaching = true)
    {
        if (empty($this->id)) {
            // Not identified => no rights
            return false;
        }
        $role = $this->getState("roles");
        if ($role === 'superadministrator') {
            return true; // superadmin role has access to everything
        }
        // allow access if the operation request is the current user's role
        return ($operation === $role);
    }
}