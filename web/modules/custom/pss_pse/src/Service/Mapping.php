<?php

namespace Drupal\pss_pse\Service;

/**
 * Class Mapping
 *
 * @package Drupal\pss_pse\Service
 */
class Mapping {

  const DATA_TYPE = [
    "PROSPECT_DATE_NAISSANCE" => "DATE",
    "PROTECTION_CONJOINT" => "BOOLEAN",
    "PROTECTION_ENFANTS" => "BOOLEAN",
    "REGIME_OBLIGATOIRE" => "STRING",
    "CODE_POSTAL" => "STRING",
    "CODE_NIVEAU_PSE" => "STRING",
    "CODE_PH" => "NUMBER",
    "STRUCTURE_COTISATION" => "STRING",
    "REDUCTION_TNS" => "BOOLEAN",
    "BUDGET_MALIN" => "BOOLEAN",
  ];

}
