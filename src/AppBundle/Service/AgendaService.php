<?php

namespace AppBundle\Service;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AgendaService
 *
 * @author PedroRossi
 */
class AgendaService {
    public static function paymentDay($dia){
        switch ($dia)
        {
            case "Domingo":
                $dia = 0;
                break;
            case "Segunda":
                $dia = 1;
                break;
            case "Terça":
                $dia = 2;
                break;
            case "Quarta":
                $dia = 3;
                break;
            case "Quinta":
                $dia = 4;
                break;
            case "Sexta":
                $dia = 5;
                break;
            case "Sábado":
                $dia = 6;
                break;
        }
        return $dia;
    }

    public static function translateDay($dia){
        switch ($dia)
        {
            case "Sunday":
                $dia = "Domingo";
                break;
            case "Monday":
                $dia = "Segunda";
                break;
            case "Tuesday":
                $dia = "Terça";
                break;
            case "Wednesday":
                $dia = "Quarta";
                break;
            case "Thursday":
                $dia = "Quinta";
                break;
            case "Friday":
                $dia = "Sexta";
                break;
            case "Saturday":
                $dia = "Sábado";
                break;
        }
        return $dia;
    }
}
