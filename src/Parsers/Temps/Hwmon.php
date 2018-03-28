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
 */

namespace Linfo\Parsers\Temps;


use Linfo\Common;
use Linfo\Parsers\Parser;

class Hwmon implements Parser
{
    final private function __construct()
    {
    }

    final private function __clone()
    {
    }


    public static function work()
    {
        $paths = \glob('/sys/class/hwmon/hwmon*/{,device/}*_input', \GLOB_NOSORT | \GLOB_BRACE);
        if (false === $paths) {
            return null;
        }

        $hwmonVals = [];
        foreach ($paths as $path) {
            $initPath = \rtrim($path, 'input');
            $value = Common::getContents($path);
            $base = \basename($path);
            $labelPath = $initPath . 'label';
            $driverName = \basename(\readlink(\dirname($path) . '/driver')) ?: null;

            // Temperatures
            if (\is_file($labelPath) && \mb_strpos($base, 'temp') === 0) {
                $label = Common::getContents($labelPath);
                $value /= $value > 10000 ? 1000 : 1;
                $unit = 'C'; // I don't think this is ever going to be in F
            } // Fan RPMs
            elseif (\preg_match('/^fan(\d+)_/', $base, $m)) {
                $label = 'fan' . $m[1];
                $unit = 'RPM';
            } // Volts
            elseif (\preg_match('/^in(\d+)_/', $base, $m)) {
                $unit = 'V';
                $value /= 1000;
                $label = Common::getContents($labelPath) ?: 'in' . $m[1];
            } else {
                continue;
            }

            // Append values
            $hwmonVals[] = [
                'path' => null,
                'name' => $label . ($driverName ? ' (' . $driverName . ')' : ''),
                'temp' => $value,
                'unit' => $unit,
            ];
        }

        return $hwmonVals;
    }
}