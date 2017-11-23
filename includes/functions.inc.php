<?php
    $sessionStarted = false;
    /**
     * Initialise les variables nécessaires pour faire fonctionner le site
     */
    function init() {
        global $sessionStarted;

        if(!$sessionStarted) {
			session_start();
			$sessionStarted = true;
		}

        if(!array_key_exists('Favoris', $_SESSION)) {
            $_SESSION['Favoris'] = array();
        }
		if(isset($_GET['logout'])) { //TODO: mettre ça dans user_functions.php
			session_destroy();
			$_SESSION = array();
			$_SESSION['Favoris'] = array();
			$_POST = array();
		}
    }

    /**
     * Normalise une chaîne de caractères avant la vérification
     * @param $s string Chaîne à normaliser
     * @return string Chaîne pouvant être vérifiée
     */
	function normaliserCaracteres($s) {
		$normalizeChars = array(
			'Š'=>'S', 'š'=>'s', 'Ð'=>'Dj','Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A',
			'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I',
			'Ï'=>'I', 'Ñ'=>'N', 'Ń'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
            'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss','à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a',
            'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i',
            'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ń'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o',
            'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ü'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', 'ƒ'=>'f', 'ă'=>'a',
            'ș'=>'s', 'ț'=>'t', 'Ă'=>'A', 'Ș'=>'S', 'Ț'=>'T',
		);
		return strtr($s, $normalizeChars);
	}

    /**
     * Donne l'URL d'une photo de cocktail
     * @param $key string Nom de l'image
     * @param $boolRenvoyerImageParDefaut bool Vrai si la fonction doit renvoyr une image par défaut
     * @return string
     */
	function getImageURL($key, $boolRenvoyerImageParDefaut) {
		$url_photo = normaliserCaracteres($key);
		$url_photo = ucfirst(strtolower($url_photo));
		$url_photo = "resources/Photos/".strtr($url_photo, ' ', '_').".jpg";
		
		if($boolRenvoyerImageParDefaut)
			return file_exists($url_photo) ? $url_photo : "resources/Photos/none.jpg";
		else
			return file_exists($url_photo) ? $url_photo : "";
	}

    /**
     * Donne la durée écoulée depuis une certaine date
     * @param $t int Timestamp
     * @return string Temps écoulé
     */
	function calculerTemps($t) {
		$temps = time() - $t;
		
		if(floor($temps / 604800))
			return floor($temps / 604800)." sem.";
		else if(floor($temps / 86400))
			return floor($temps / 86400)." jours";
		else if(floor($temps / 3600))
			return floor($temps / 3600)." h.";
		else if(floor($temps / 60))
			return floor($temps / 60)." min.";
		else
			return $temps." sec.";
	}

    /**
     * Affiche une liste de cocktails
     * @param $tab array Tableau contenant les cocktails
     * @param $isFavoris bool Vrai si le tableau est celui des favoris
     */
	function afficherCocktails($tab, $isFavoris) {
		global $Recettes;
		$i = 0;

		foreach($tab as $key => $value) {
			$recette = ($isFavoris ? $Recettes[$key] : $value);
?>
            <div class="item" style="animation-delay: <?php echo $i < 0.5 ? $i+=0.06 : $i; ?>s">
                <a href='<?php echo "?R=Cocktail&K=".$key; ?>'></a>
                <?php if(array_key_exists($key, $_SESSION['Favoris'])) echo "
					<i class='fa fa-star-o'></i>";
                ?>

                <div class="image" style="background-image: url('<?php echo getImageURL($recette["titre"], true); ?>')"></div>
                <h4><?php echo $recette["titre"]; ?></h4>
                <div>
                    <i class='fa fa-tint'></i>
                    <p><?php
						foreach($recette['index'] as $ing) {
							echo $ing." <br/>";
						}
						?></p>
                </div>
                <span class="time"><?php if(array_key_exists($key, $_SESSION['Favoris'])) {$t = calculerTemps($_SESSION['Favoris'][$key]); echo "<i class='fa fa-star-half-o'></i> il y a ".$t;} ?></span>
            </div>

<?php
		}
	}

    /**
     * Donne le chemin d'un fichier de templates
     * @param $nom string Nom de la template
     * @return string Chemin de la template
     */
    function getFichierTemplate($nom) {
        $chemin = __DIR__ . '/../templates/' . strtolower($nom) . '.inc.php';

        if(file_exists($chemin)) {
            return $chemin;
        }
        else {
            http_response_code(404); //TODO: retourner plutôt une exception
            return __DIR__ . '/../templates/404.inc.php';
        }
    }
?>