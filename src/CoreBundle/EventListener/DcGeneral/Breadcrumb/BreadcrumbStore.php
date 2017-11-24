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

namespace MetaModels\CoreBundle\EventListener\DcGeneral\Breadcrumb;

use MetaModels\CoreBundle\Assets\IconBuilder;
use Symfony\Component\Translation\TranslatorInterface;

class BreadcrumbStore
{
    /**
     * The elements.
     *
     * @var array
     */
    private $elements = [];

    /**
     * The icon builder.
     *
     * @var IconBuilder
     */
    private $iconBuilder;

    /**
     * The translator.
     *
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * List of "current" ids.
     *
     * @var string[]
     */
    private $idList = [];

    /**
     * The current URI.
     *
     * @var string
     */
    private $uri;

    /**
     * Create a new instance.
     *
     * @param IconBuilder         $iconBuilder The icon builder.
     * @param TranslatorInterface $translator  The translator.
     * @param string              $uri         The current URI.
     */
    public function __construct(IconBuilder $iconBuilder, TranslatorInterface $translator, $uri)
    {
        $this->iconBuilder = $iconBuilder;
        $this->translator  = $translator;
        $this->uri         = $uri;
    }

    /**
     * Push an entry.
     *
     * @param $url
     * @param $table
     * @param $icon
     *
     * @return void
     */
    public function push($url, $table, $icon)
    {
        $this->elements[] = [
            'url' => $url,
            'text' => $this->getLabel($table),
            'icon' => $this->iconBuilder->getBackendIcon($icon)
        ];
    }

    /**
     * Test if an id has been set.
     *
     * @param string $table The table name.
     *
     * @return bool
     */
    public function hasId($table)
    {
        return array_key_exists($table, $this->idList);
    }

    /**
     * Set an id.
     *
     * @param string $table The table name.
     * @param string $id    The id.
     *
     * @return void
     */
    public function setId($table, $id)
    {
        $this->idList[$table] = $id;
    }

    /**
     * Get an id.
     *
     * @param string $table The table name.
     *
     * @return null|string
     */
    public function getId($table)
    {
        return $this->hasId($table) ? $this->idList[$table] : null;
    }

    /**
     * Retrieve uri.
     *
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Get for a table the human readable name or a fallback.
     *
     * @param string $table Name of table.
     *
     * @return string The human readable name.
     */
    public function getLabel($table)
    {
        if ('tl_' !== substr($table, 0, 3)) {
            return $table;
        }
        $shortTable = str_replace('tl_', '', $table);
        $label      = $this->translator->trans( 'BRD.' . $shortTable, [], 'contao_default');
        if ($label == $shortTable) {
            $shortTable = str_replace('tl_metamodel_', '', $table);
            return ucfirst($shortTable) . ' %s';
        }

        return specialchars($label);
    }

    /**
     * Retrieve the elements.
     *
     * @return array
     */
    public function getElements()
    {
        return $this->elements;
    }
}
