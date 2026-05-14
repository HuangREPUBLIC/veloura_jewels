<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Cache\Cache;
use Cake\Controller\Controller;
use Cake\Event\EventInterface;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/5/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('FormProtection');`
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('Flash');
        $this->loadComponent('Authentication.Authentication');

        /*
         * Enable the following component for recommended CakePHP form protection settings.
         * see https://book.cakephp.org/5/en/controllers/components/form-protection.html
         */
        //$this->loadComponent('FormProtection');
    }

    public function beforeRender(EventInterface $event): void
    {
        parent::beforeRender($event);
        $siteSettings = Cache::remember('site_settings_global', function () {
            return $this->fetchTable('PageContents')->getForPage('global');
        }, 'default');
        $this->set('siteSettings', $siteSettings);
    }

    protected function requireRole(array $roles): void
    {
        $identity = $this->Authentication->getIdentity();
        if (!$identity || !in_array($identity->get('role'), $roles, true)) {
            $this->Flash->error('You do not have permission to access this page.');
            $this->redirect('/');
        }
    }

    protected function logActivity(string $model, int $modelId, string $action, string $modelLabel = '', ?array $changes = null): void
    {
        try {
            $identity = $this->Authentication->getIdentity();
            $this->fetchTable('ActivityLogs')->save(
                $this->fetchTable('ActivityLogs')->newEntity([
                    'user_id'     => $identity?->get('id'),
                    'user_name'   => $identity?->get('name') ?? $identity?->get('email') ?? 'System',
                    'action'      => $action,
                    'model'       => $model,
                    'model_id'    => $modelId,
                    'model_label' => $modelLabel,
                    'changes'     => $changes,
                    'created'     => new \Cake\I18n\DateTime(),
                ])
            );
        } catch (\Exception $e) {
            // Never let logging failure break the main flow
        }
    }
}
