<?php

/**
 * This file is part of Linfo (c) 2010 Joseph Gillotti.
 *
 * Linfo is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Linfo is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Linfo. If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace Linfo\Parsers\Temps;


use Linfo\Common;
use Linfo\Parsers\Parser;

class ThermalZone implements Parser
{
    final private function __construct()
    {
    }

    final private function __clone()
    {
    }

    public static function work()
    {
        $paths = \glob('/sys/class/thermal/thermal_zone*', \GLOB_NOSORT | \GLOB_BRACE);
        if (false === $paths) {
            return null;
        }

        $thermalZoneVals = [];
        foreach ($paths as $path) {
            $labelPath = $path . '/type';
            $valuePath = $path . '/temp';

            $label = Common::getContents($labelPath);
            $value = Common::getContents($valuePath);

            if (null === $label || null === $value) {
                continue;
            }

            $value /= $value > 10000 ? 1000 : 1;

            $thermalZoneVals[] = [
                'path' => $path,
                'name' => $label,
                'temp' => $value,
                'unit' => 'C', // I don't think this is ever going to be in F
            ];
        }

        return $thermalZoneVals;
    }
}