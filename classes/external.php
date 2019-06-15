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
 * @package    local_recompletion
 * @copyright  2019 Joseph Conradt
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_recompletion;

use local_recompletion\task\check_recompletion;

defined('MOODLE_INTERNAL') || die();

require_once("$CFG->libdir/externallib.php");
require_once("$CFG->dirroot/local/recompletion/locallib.php");

class external extends \external_api
{
    #region reset_user

    /**
     * Returns description of reset_user() parameters.
     *
     * @return \external_function_parameters
     */
    public static function reset_user_parameters()
    {
        return new \external_function_parameters([
            'userid' => new \external_value(PARAM_INT, 'Record ID of user to reset'),
            'courseid' => new \external_value(PARAM_INT, 'Record ID of course to reset user completion'),
            'config' => new \external_single_structure([
                'archivecompletiondata' => new \external_value(PARAM_BOOL, get_string('archivecompletiondata_help', 'local_recompletion'), VALUE_DEFAULT, true),
                'deletegradedata' => new \external_value(PARAM_BOOL, get_string('deletegradedata_help', 'local_recompletion'), VALUE_DEFAULT, true),
                'quizdata' => new \external_value(PARAM_INT, get_string('quizattempts_help', 'local_recompletion'), VALUE_DEFAULT, LOCAL_RECOMPLETION_DELETE),
                'archivequizdata' => new \external_value(PARAM_BOOL, get_string('archive', 'local_recompletion'), VALUE_DEFAULT, true),
                'assigndata' => new \external_value(PARAM_INT, get_string('assignattempts_help', 'local_recompletion'), VALUE_DEFAULT, LOCAL_RECOMPLETION_DELETE),
                'archivescormdata' => new \external_value(PARAM_BOOL, get_string('archive', 'local_recompletion'), VALUE_DEFAULT, true),
                'scormdata' => new \external_value(PARAM_INT, get_string('scormattempts_help', 'local_recompletion'), VALUE_DEFAULT, LOCAL_RECOMPLETION_DELETE),
                'recompletionemailenable' => new \external_value(PARAM_BOOL, get_string('recompletionemailenable_help', 'local_recompletion'), VALUE_DEFAULT, false),
                'recompletionemailbody' => new \external_value(PARAM_RAW, get_string('recompletionemailbody_help', 'local_recompletion'), VALUE_DEFAULT, ''),
                'recompletionemailsubject' => new \external_value(PARAM_TEXT, get_string('recompletionemailsubject_help', 'local_recompletion'), VALUE_DEFAULT, ''),
            ], '', VALUE_DEFAULT, []),
        ]);
    }

    public static function reset_user($userid, $courseid, $config)
    {
        global $DB;
        
        $params = self::validate_parameters(self::reset_user_parameters(), [
            'userid' => $userid,
            'courseid' => $courseid,
            'config' => $config
        ]);
        
        $course = $DB->get_record('course', ['id' => $params['courseid']]); 

        $reset = new check_recompletion();
        $reset->reset_user($params['userid'], $course, (object)$params['config']);
    }

    /**
     * Returns description of reset_user() result value.
     *
     * @return \external_description
     */
    public static function reset_user_returns()
    {
        return null;
    }

    #endregion
}