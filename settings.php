<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Plugin settings for the local_examalert plugin.
 *
 * @package   local_examalert
 * @copyright Year, You Name <your@email.address>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

global $PAGE;

// Ensure the configurations for this site are set
if ($hassiteconfig) {

    $PAGE->requires->js_call_amd('local_examalert/settingsdynamic', 'set_input', []);

    // Create the new settings page
    // - in a local plugin this is not defined as standard, so normal $settings->methods will throw an error as
    // $settings will be null
    $settings = new admin_settingpage('local_examalert', 'Exam Alert');

    // Create
    $ADMIN->add('localplugins', $settings);

    // Add a setting field to the settings for this page
    $settings->add(new admin_setting_configcheckbox(
    // This is the reference you will use to your configuration
        'local_examalert/active',

        // This is the friendly title for the config, which will be displayed
        'Actived',

        // This is helper text for this config field
        'Choose if the Alert is visible for the choosen period',

        // This is the default value
        false
    ));

    $settings->add(new admin_setting_configcheckbox(
    // This is the reference you will use to your configuration
        'local_examalert/datemode',

        // This is the friendly title for the config, which will be displayed
        'Quizz Period',

        // This is helper text for this config field
        'Choose if you want to display the warning during the quizz period or the period selected below.',

        // This is the default value
        false
    ));

    $settings->add(new admin_setting_configtext(
    // This is the reference you will use to your configuration
        'local_examalert/datebegin',

        // This is the friendly title for the config, which will be displayed
        'Begin Date',

        // This is helper text for this config field
        'Choose the start date of the period',

        // This is the default value
        date("d/m/Y"),

        // This is the type of Parameter this config is
        PARAM_TEXT
    ));

    $due_dt = new DateTime();
    $due_dt->modify('+1 day');

    $settings->add(new admin_setting_configtext(
    // This is the reference you will use to your configuration
        'local_examalert/dateend',

        // This is the friendly title for the config, which will be displayed
        'End Date',

        // This is helper text for this config field
        'Choose the end date of the period',

        // This is the default value
        date_format($due_dt, 'd/m/Y'),

        // This is the type of Parameter this config is
        PARAM_TEXT
    ));
}