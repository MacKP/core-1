<?php

/**
 * This file is part of MetaModels/core.
 *
 * (c) 2012-2017 The MetaModels team.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    MetaModels
 * @subpackage Core
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @copyright  2012-2017 The MetaModels team.
 * @license    https://github.com/MetaModels/core/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

namespace MetaModels\CoreBundle\EventListener\DcGeneral\Table\SearchablePages;

use ContaoCommunityAlliance\DcGeneral\Contao\RequestScopeDeterminator;
use ContaoCommunityAlliance\DcGeneral\Contao\View\Contao2BackendView\Event\GetPropertyOptionsEvent;
use Doctrine\DBAL\Connection;

/**
 * This handles the providing of available render settings.
 */
class RenderSettingOptionListener extends AbstractAbstainingListener
{
    /**
     * The connection.
     *
     * @var Connection
     */
    private $connection;

    /**
     * Create a new instance.
     *
     * @param RequestScopeDeterminator $scopeDeterminator The scope determinator.
     * @param Connection               $connection        The connection.
     */
    public function __construct(RequestScopeDeterminator $scopeDeterminator, Connection $connection)
    {
        parent::__construct($scopeDeterminator);
        $this->connection = $connection;
    }

    /**
     * Retrieve the options for the attributes.
     *
     * @param GetPropertyOptionsEvent $event The event.
     *
     * @return void
     */
    public function handle(GetPropertyOptionsEvent $event)
    {
        if (!$this->wantToHandle($event) || ($event->getPropertyName() !== 'rendersetting')) {
            return;
        }
        $model = $event->getModel();
        $pid   = $model->getProperty('pid');
        if (empty($pid)) {
            return;
        }
        $filters = $this->connection
            ->createQueryBuilder()
            ->select('id', 'name')
            ->from('tl_metamodel_rendersettings')
            ->where('pid=:id')
            ->setParameter('id', $model->getProperty('pid'))
            ->execute()
            ->fetchAll(\PDO::FETCH_ASSOC);

        $options = [];
        foreach ($filters as $filter) {
            $options[$filter['id']] = $filter['name'];
        }

        $event->setOptions($options);
    }
}