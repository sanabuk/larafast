<?php
namespace sanabuk\larafast;

/**
 * Essai pour une gestion dynamique des différentes clés nécessaires aux relations
 * Cette gestion permettrait d'éviter l'édition du fichier de config
 * D'un autre côté l'édition de cette config permet au dev de garder la main sur ce qu'il met à disposition
 */

class HandlerKeyName
{
	protected $relation;

	public function __construct($relation){
		$this->relation = $relation;
		dd(substr($relation, strrpos($relation, '\\') + 1));
	}
}