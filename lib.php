<?php

function local_examalert_extend_navigation(global_navigation $navigation)
{
    global $PAGE, $DB, $COURSE;
    
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
            // Begin date
            $db = date('Y/m/d/H/i', 962110904);
            $datebegin = date_create();

            $datebegin->setDate(explode(
                "/", $db)[0],
                explode("/", $db)[1],
                explode("/", $db)[2]
            );

            $datebegin->setTime(explode("/", $db)[3], explode("/", $db)[4]);

            // End date
            $dateend = date_create();
            $df = date('Y/m/d/H/i', 32519019704);

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
            $message = "Attention, ce message vous est adressé car nous sommes en période d'examen ou dans les horaires d'ouverture de votre test.<br /> 
            Si des étudiants sont en train de passer votre test, il est important de noter que modifier les paramètres de ce test à ce stade peut avoir<br /> 
            des conséquences néfastes sur l'expérience des étudiants et les résultats obtenus. <br />
            Cela s'applique particulièrement à des éléments tels que la durée du test et les modes de notation. <br />
            Les dérogations spécifiques accordées aux étudiants ne sont pas concernées par cette restriction et peuvent être réalisées pendant le test, 
            <br />le cas échéant. Nous vous demandons donc de bien prendre en compte ces aspects avant de procéder à toute modification du test. <br />
            Si vous avez des doutes ou des questions, n'hésitez pas à contacter sos-dapi@univ-grenoble-alpes.fr. <br />
            
            <a href='javascript:void(0);' ONCLICK='document.getElementById(\"warning_message\").style.display = \"none\";document.getElementById(\"mask\").style.display = \"none\"'>Je souhaite malgré tout accéder au paramétrage du test</a>
            <br />
            <a href='javascript:void(0);' ONCLICK='document.location.href=\"https://moodle-test.grenet.fr/moodle_flo/course/view.php?id=".$COURSE->id."\"'>Ne pas modifier</a>";

            echo html_writer::div('', '', array('id' => 'mask'));

            echo html_writer::div($message, 'box py-3 generalbox alert alert-error alert alert-danger', array('id' => 'warning_message')); // <div class="toad" id="tophat">Mr</div>
        }
    }
}