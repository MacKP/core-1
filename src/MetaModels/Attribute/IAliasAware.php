<?php

/**
 * This file is part of MetaModels/core.
 *
 * (c) 2012-2015 The MetaModels team.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    MetaModels
 * @subpackage Core
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Stefan Heimes <stefan_heimes@hotmail.com>
 * @copyright  2012-2015 The MetaModels team.
 * @license    https://github.com/MetaModels/core/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

namespace MetaModels\Attribute;

/**
 * This interface allows to identify attributes which have a alias value like the select and tag attribute. So other
 * attribute can use this alias values.
 */
interface IAliasAware
{
    /**
     * Return the value of the alias.
     *
     * @return string
     */
    public function getAliasValue();

    /**
     * Check if the alias field is a metamodels attribute. If so we can handel it with some more options.
     *
     * @return bool True means we have a MetaModels attribute.
     */
    public function isAliasMetaModels();

    /**
     * If the alias is a MetaModels alias parse it.
     *
     * @param string $format The format for the parsing.
     *
     * @return string The parsed string or as fallback the getAliasValue.
     */
    public function parseAliasValue($format = 'text');
}