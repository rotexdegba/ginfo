<?php

/**
 * This file is part of Linfo (c) 2014, 2015 Joseph Gillotti.
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
namespace Linfo\Tests;

use Linfo\Linfo;

class LinfoTest extends \PHPUnit\Framework\TestCase
{
    public function testTodo()
    {
        $linfo = new Linfo();
        $info = $linfo->getInfo();

        //\print_r($info->getGeneral());
        //\print_r($info->getCpu());
        //\print_r($info->getMemory());
        //\print_r($info->getSoundCard());
        //\print_r($info->getUsb());
        //\print_r($info->getPci());
        //\print_r($info->getNetwork());
        //\print_r($info->getDisk());
        //\print_r($info->getBattery());
        //\print_r($info->getTemps());
        //\print_r($info->getProcesses());
        \print_r($info->getServices());
    }
}
