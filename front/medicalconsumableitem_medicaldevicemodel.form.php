<?php
/**
 * ---------------------------------------------------------------------
 * GLPI - Gestionnaire Libre de Parc Informatique
 * Copyright (C) 2015-2021 Teclib' and contributors.
 *
 * http://glpi-project.org
 *
 * based on GLPI - Gestionnaire Libre de Parc Informatique
 * Copyright (C) 2003-2014 by the INDEPNET Development Team.
 *
 * ---------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of GLPI.
 *
 * GLPI is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * GLPI is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with GLPI. If not, see <http://www.gnu.org/licenses/>.
 * ---------------------------------------------------------------------
 */

/**
 * @since 0.84
 */

use Glpi\Event;

include ('../inc/includes.php');

$cipm = new PluginOpenmedisMedicalConsumableItem_MedicalDeviceModel();
if (isset($_POST["add"])) {
   $cipm->check(-1, CREATE, $_POST);
   if ($cipm->add($_POST)) {
      Event::log($_POST["plugin_openmedis_medicalconsumableitems_id"], "medicalconsumables", 4, "inventory",
                 //TRANS: %s is the user login
                 sprintf(__('%s associates a type'), $_SESSION["glpiname"]));
   }
   Html::back();

}
Html::displayErrorAndDie('Lost');