<?php

namespace app\rbac;

use yii\rbac\Rule;

/**
 * Checks if authorID matches user passed via params
 */
class ArtistOrBetterRule extends Rule
{
    public $name = 'isArtistorBetter';

    /**
     * @param string|integer $user the user ID.
     * @param Item $item the role or permission that this rule is associated with
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return boolean a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($item, $params)
    {
       return (isset($params['team']) && isset($params['user'])) ? 
            ($params['team']->getRoleByUserid($params['user']->getId()) <= 1) || array_key_exists("admin",\Yii::$app->authManager->getRolesByUser($params['user']->getId())) : 
            false;
    }
}

?>