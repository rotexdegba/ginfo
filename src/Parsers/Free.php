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

namespace Linfo\Parsers;


use Symfony\Component\Process\Process;

class Free implements Parser
{
    final private function __construct()
    {
    }

    final private function __clone()
    {
    }

    public static function work() : ?array
    {
        $process = new Process('LANG=C free -bw');
        $process->run();

        if (!$process->isSuccessful()) {
            return null;
        }

        $free = $process->getOutput();

        $arr = \explode("\n", \trim($free));
        \array_shift($arr); // remove header

        $memStr = \trim(\explode(':', $arr[0], 2)[1]);
        $swapStr = \trim(\explode(':', $arr[1], 2)[1]);

        list($memTotal, $memUsed, $memFree, $memShared, $memBuffers, $memCached, $memAvailable) = \preg_split('/\s+/', $memStr);
        list($swapTotal, $swapUsed, $swapFree) = \preg_split('/\s+/', $swapStr);

        return [
            'total' => $memTotal,
            'used' => $memUsed,
            'free' => $memFree,
            'shared' => $memShared,
            'buffers' => $memBuffers,
            'cached' => $memCached,
            'available' => $memAvailable,

            'swapTotal' => $swapTotal,
            'swapUsed' => $swapUsed,
            'swapFree' => $swapFree,
        ];
    }
}
