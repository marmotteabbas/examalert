<?php

function local_examalert_extend_navigation(global_navigation $navigation)
{
    global $PAGE, $DB;

    $myconfig = get_config('local_examalert');


    $r = array();

    if (strpos($PAGE->url->get_path(), "course/modedit.php") !== false) {

        $sql = "SELECT cm.id FROM {course_modules} cm 
                INNER JOIN {modules} m ON cm.module = m.id 
                WHERE cm.id = :id AND m.name = 'quiz'";

         $r = $DB->get_records_sql($sql, array("id" => $_GET['update']));
    }


    if (strpos($PAGE->url->get_path(), "/mod/quiz/edit") !== false || (strpos($PAGE->url->get_path(), "course/modedit.php") !== false && count($r) >0)) {

        if ($myconfig->datemode == 0) {
            //DATE DE DEBUT
            $datebegin = date_create();
            $datebegin->setDate(explode(
                "/", $myconfig->datebegin)[2],
                explode("/", $myconfig->datebegin)[1],
                explode("/", $myconfig->datebegin)[0]
            );
            $datebegin->setTime(0, 0);

            //DATE DE FIN
            $dateend = date_create();
            $dateend->setDate(explode(
                "/", $myconfig->dateend)[2],
                explode("/", $myconfig->dateend)[1],
                explode("/", $myconfig->dateend)[0]
            );
            $dateend->setTime(23, 59);
        } else {

            $sql = "SELECT q.timeopen, q.timeclose FROM course_modules cm 
                INNER JOIN quiz q ON cm.instance = q.id 
                WHERE cm.id = :id";

            $dates = $DB->get_record_sql($sql, array("id" => $_GET['update']));

            // Begin date
            $db = date('Y/m/d/H/i', $dates->timeopen);
            $datebegin = date_create();

            $datebegin->setDate(explode(
                "/", $db)[0],
                explode("/", $db)[1],
                explode("/", $db)[2]
            );

            $datebegin->setTime(explode("/", $db)[3], explode("/", $db)[4]);

            // End date
            $dateend = date_create();
            $df = date('Y/m/d/H/i', $dates->timeclose);

            $dateend->setDate(explode(
                "/", $df)[0],
                explode("/", $df)[1],
                explode("/", $df)[2]
            );

            $dateend->setTime(explode("/", $df)[3], explode("/", $df)[4]);

        }

        $currentdate = new dateTime("now");

        if ($myconfig->active == 1 && ((($datebegin < $currentdate) || ($datebegin->getTimestamp() == 0 && $dateend->getTimestamp() !== 0)) && (($dateend > $currentdate) || ($dateend->getTimestamp() == 0) && $datebegin->getTimestamp() !== 0))) {
            $PAGE->requires->css('/local/examalert/style.css', true);
            $message = "Nous sommes en prédiode d'examen si vous modifiez quelque chose, cela peut avoir de graves conséquences ! <br /><a href='javascript:void(0);' ONCLICK='document.getElementById(\"warning_message\").style.display = \"none\";document.getElementById(\"mask\").style.display = \"none\"'>Jai compris.</a>";

            echo html_writer::div('', '', array('id' => 'mask'));

            echo html_writer::div($message, 'box py-3 generalbox alert alert-error alert alert-danger', array('id' => 'warning_message')); // <div class="toad" id="tophat">Mr</div>
        }
    }
}