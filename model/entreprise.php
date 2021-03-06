<?php
    require_once('bdd.php');

    class entreprise {
        private $_bdd;
        private $_titre;
        private $_logo;
        private $_video;
        private $_phrase;
        private $_description;
        private $_telephone;
        private $_numeroDeRue;
        private $_rue;
        private $_ville;
        private $_cp;

        public function __construct() {
            $this->_bdd = new bdd;

            $bdd = $this->_bdd;
            $bdd = $bdd->co();

            $req = $bdd->query('SELECT * FROM entreprise WHERE id = 1');
            $resultat = $req->fetch();
            
            $req->closeCursor();
            $bdd = null;

            $this->_titre = $resultat['titre'];
            $this->_logo = $resultat['logo'];
            $this->_video = $resultat['video'];
            $this->_phrase = $resultat['phrase'];
            $this->_description = $resultat['description'];
            $this->_telephone = $resultat['telephone'];
            $this->_numeroDeRue = $resultat['numeroRue'];
            $this->_rue = $resultat['rue'];
            $this->_ville = $resultat['ville'];
            $this->_cp = $resultat['cp'];
        }

        /// setter ///
        public function setterlogo($file) {
            if (is_array($file) && isset($file['name'], $file['type'], $file['tmp_name'])) {
                switch ($file['type']) {
                    case 'image/gif':
                    case 'image/jpeg':
                    case 'image/png':
                    case 'image/svg+xml':

                        $type = true;
                        break;
                    
                    default:
                        $type = false;
                        break;
                }

                if ($type) {
                    if (move_uploaded_file($file['tmp_name'], 'src/logo/' . $file['name'])) {
                        $logo = $this->logo();
                        $bdd = $this->_bdd;
                        $bdd = $bdd->co();

                        $req = $bdd->prepare('UPDATE entreprise SET logo = :logo WHERE id = 1');

                        if ($logo) {
                            $array = array(
                                ':logo' => $file['name']
                            );

                            if ($req->execute($array)) {
                                $req->closecursor();
                                $bdd = null;

                                unlink('src/logo/' . $logo);
                                $this->_logo = $file['name'];

                                return array(
                                    'result' => true,
                                    'text' => 'Le logo a bien été mis à jour',
                                    'img' => $file['name']
                                );
                            }else {
                                $req->closecursor();
                                $bdd = null;

                                unlink('src/logo/' . $file['name']);

                                return array(
                                    'result' => false,
                                    'text' => 'La base de données n\'a pas pu être mis à jour'
                                );
                            }
                        }else {
                            $array = array(
                                ':logo' => $file['name']
                            );

                            if ($req->execute($array)) {
                                $req->closecursor();
                                $bdd = null;

                                $this->_logo = $file['name'];

                                return array(
                                    'result' => true,
                                    'text' => 'Le logo a bien été mis à jour',
                                    'img' => $file['name']
                                );
                            }else {
                                $req->closecursor();
                                $bdd = null;

                                unlink('src/logo/' . $file['name']);

                                return array(
                                    'result' => false,
                                    'text' => 'La base de données n\'a pas pu être mis à jour'
                                );
                            }
                        }
                    }else {
                        return array(
                            'result' => false,
                            'text' => 'Le fichier n\'a pas été envoyer au serveur'
                        );
                    }
                }else {
                    return array(
                        'result' => false,
                        'text' => 'Seul les .gif, .jpeg, .png et .svg sont autoriser'
                    );
                }
            }else {
                return array(
                    'result' => false,
                    'text' => 'Vous n\'avez pas envoyé de fichier'
                );
            }
        }

        public function settertitre($titre) {
            if (is_string($titre) && $titre != '' && strlen($titre) <= 50) {
                $oldtitre = $this->titre();

                if ($oldtitre != $titre) {
                    $bdd = $this->_bdd;
                    $bdd = $bdd->co();

                    $req = $bdd->prepare('UPDATE entreprise SET titre = :titre WHERE id = 1');
                    $array = array(
                        ':titre' => $titre
                    );

                    if ($req->execute($array)) {
                        $req->closecursor();
                        $bdd = null;

                        $this->_titre = $titre;

                        return array(
                            'result' => true,
                            'text' => 'Le titre a été mis à jour'
                        );
                    }else {
                        $req->closecursor();
                        $bdd = null;

                        return array(
                            'result' => false,
                            'text' => 'Le titre n\'a pas pu être mis à jour'
                        );
                    }
                }else {
                    return array(
                        'result' => false,
                        'text' => 'Ce titre est déja utilisé'
                    );
                }
            }elseif (strlen($titre) > 50) {
                return array(
                    'result' => false,
                    'text' => 'Le titre doit faire moins de 50 caractères. Il est actuellement de ' . strlen($titre) . ' caractères'
                );
            }elseif ($titre == '') {
                return array(
                    'result' => false,
                    'text' => 'Vous avez envoyé du texte vide'
                );
            }else {
                return array(
                    'result' => false,
                    'text' => 'Une erreur c\'est produit'
                );
            }
        }

        public function setterphrase($phrase) {
            if (is_string($phrase)) {
                if (strlen($phrase) <= 50) {
                    $oldphrase = $this->phrase();

                    if ($oldphrase != $phrase) {
                        $bdd = $this->_bdd;
                        $bdd = $bdd->co();

                        $req = $bdd->prepare('UPDATE entreprise SET phrase = :phrase WHERE id = 1');

                        if ($phrase == '') {
                            $array = array(
                                ':phrase' => null
                            );
                        }else {
                            $array = array(
                                ':phrase' => $phrase
                            );
                        }

                        if ($req->execute($array)) {
                            $req->closecursor();
                            $bdd = null;

                            if ($phrase == '') {
                                $this->_phrase = null;
                            }else {
                                $this->_phrase = $phrase;
                            }

                            return array(
                                'result' => true,
                                'text' => 'Le slogan a bien été modifier'
                            );
                        }else {
                            $req->closecursor();
                            $bdd = null;

                            return array(
                                'result' => false,
                                'text' => 'Ce slogan n\'a pas pu être envoyé à la base de données'
                            );
                        }
                    }else {
                        return array(
                            'result' => false,
                            'text' => 'Ce slogan est déja utilisé'
                        );
                    }
                }else {
                    return array(
                        'result' => false,
                        'text' => 'Le titre ne peut faire 50 caractères maximum. Il est actuellement de ' . strlen($phrase) . ' caractères'
                    );
                }
            }else {
                return array(
                    'result' => false,
                    'text' => 'Seul le texte est autorisé'
                );
            }
        }

        public function setternumero($tel) {
            if (is_int($tel) && $tel != 0 && strlen($tel) == 9 || $tel == '') {
                $tel = 0 . $tel;
                $oldtel = $this->numero();
                
                if ($oldtel == false) {
                    $oldtel = '00';
                }

                if ($tel != $oldtel) {
                    $bdd = $this->_bdd;
                    $bdd = $bdd->co();

                    $req = $bdd->prepare('UPDATE entreprise SET telephone = :tel WHERE id = 1');

                    if ($tel == 00) {
                        $array = array(
                            ':tel' => null
                        );
                    }else {
                        $array = array(
                            ':tel' => substr($tel, 1)
                        );
                    }

                    if ($req->execute($array)) {
                        $req->closecursor();
                        $bdd = null;

                        $this->_telephone = $array[':tel'];

                        return array(
                            'result' => true,
                            'text' => 'Le nouveau numéro a bien été mis à jour'
                        );
                    }else {
                        $req->closecursor();
                        $bdd = null;

                        return array(
                            'result' => false,
                            'text' => 'Le nouveau numéro n\'a pas pu être envoyé à la base de données'
                        );
                    }
                }else {
                    return array(
                        'result' => false,
                        'text' => 'Le numéro est déja utilisé'
                    );
                }
            }else {
                return array(
                    'result' => false,
                    'text' => 'Seul les numéros de téléphone sont autorisés'
                );
            }
        }

        public function setteraddress($nbrue, $rue, $ville, $cp) {
            if ($nbrue != '' && $rue != '' && $ville != '' && $cp != '') {
                if (is_int($nbrue) && is_int($cp)) {
                    if (strlen($nbrue) <= 11 && strlen($rue) <= 50 && strlen($ville) <= 50 && strlen($cp) <= 5) {
                        if (strlen($cp) == 5) {
                            $cp = $cp;
                        }elseif (strlen($cp) == 4) {
                            $num = substr($cp, 0, 1);

                            if ($num != 0) {
                                $cp = 0 . $cp;
                            }else {
                                $cp = false;
                            }
                        }else {
                            $cp = false;
                        }

                        if ($cp) {
                            $bdd = $this->_bdd;
                            $bdd = $bdd->co();

                            $req = $bdd->prepare('UPDATE entreprise SET numeroRue = :num, rue = :rue, ville = :ville, cp = :cp WHERE id = 1');

                            $array = array(
                                ':num' => $nbrue,
                                ':rue' => $rue,
                                ':ville' => $ville,
                                ':cp' => $cp
                            );

                            if ($req->execute($array)) {
                                $req->closecursor();
                                $bdd = null;

                                return array(
                                    'result' => true,
                                    'text' => 'L\'adresse à étais mis à jour'
                                );
                            }else {
                                $req->closecursor();
                                $bdd = null;

                                return array(
                                    'result' => false,
                                    'text' => 'La nouvelle adresse n\'a pas pu être mis à jour'
                                );
                            }
                        }else {
                            return array(
                                'result' => false,
                                'text' => 'Vous n\'avez pas indiqué le code postale'
                            );
                        }
                    }elseif (strlen($nbrue) > 11) {
                        return array(
                            'result' => false,
                            'text' => 'Le numéro de rue ne peux aller jusqu\'a 99999999999'
                        );
                    }elseif (strlen($rue) > 50) {
                        return array(
                            'result' => false,
                            'text' => 'Le nom de rue ne peux contenir plus de 50 caractères. Elle est actuellement de ' . strlen($rue) . ' caractères'
                        );
                    }elseif (strlen($ville) > 50) {
                        return array(
                            'result' => false,
                            'text' => 'La ville ne peut contenir plus de 50 caractères. Elle est actuellement de ' . strlen($ville) . ' caractères'
                        );
                    }elseif (strlen($cp) > 5) {
                        return array(
                            'result' => false,
                            'text' => 'Vous n\'avez pas indiqué le code postale'
                        );
                    }else {
                        return array(
                            'result' => false,
                            'text' => 'erreur'
                        );
                    }
                }else {
                    return array(
                        'result' => false,
                        'text' => 'Le numéro de rue et le code postale doit être un nombre'
                    );
                }
            }else {
                return array(
                    'result' => false,
                    'text' => 'Il manque une ou plusieurs informations'
                );
            }
        }

        public function supadresse() {
            $bdd = $this->_bdd;
            $bdd = $bdd->co();

            $req = $bdd->prepare('UPDATE entreprise SET numeroRue = :num, rue = :rue, ville = :ville, cp = :cp WHERE id = 1');
            $array = array(
                ':num' => null,
                ':rue' => null,
                ':ville' => null,
                ':cp' => null
            );

            if ($req->execute($array)) {
                $req->closecursor();
                $bdd = null;

                return array(
                    'result' => true,
                    'text' => 'L\'adresse à étais supprimé'
                );
            }else {
                $req->closecursor();
                $bdd = null;

                return array(
                    'result' => false,
                    'text' => 'L\'adresse n\'a pas été supprimée'
                );
            }
        }

        public function setterdescription($description) {
            if (is_string($description)) {
                $bdd = $this->_bdd;
                $bdd = $bdd->co();

                $req = $bdd->prepare('UPDATE entreprise SET description = :descript WHERE id = 1');
                $array = array(
                    ':descript' => $description
                );

                if ($req->execute($array)) {
                    $req->closecursor();
                    $bdd = null;

                    $this->_description = $description;

                    return array(
                        'result' => true,
                        'text' => 'La description a été mis à jour'
                    );
                }else {
                    $req->closecursor();
                    $bdd = null;

                    return array(
                        'result' => false,
                        'text' => 'La description n\'a pas pu être mis à jour'
                    );
                }
            }else {
                return array(
                    'result' => false,
                    'text' => 'Seul du texte peut être envoyé'
                );
            }
        }

        public function supdescription() {
            $bdd = $this->_bdd;
            $bdd = $bdd->co();

            $req = $bdd->query('UPDATE entreprise SET description = NULL WHERE id = 1');

            if ($req) {
                $req->closecursor();
                $bdd = null;

                $this->_description = null;

                return array(
                    'result' => true,
                    'text' => 'La description a été supprimée'
                );
            }else {
                $req->closecursor();
                $bdd = null;

                return array(
                    'result' => true,
                    'text' => 'La description n\'a pas été supprimée'
                );
            }
        }

        public function setVideo($file) {
            $upload = $this->uploadVideo($file);

            if ($upload['result'] && strlen($file['name']) <= 50) {
                $bdd = $this->_bdd;
                $oldVideo = $this->video();

                $bdd = $bdd->co();

                $req = $bdd->prepare('UPDATE entreprise SET video = :video');
                $array = array(
                    ':video' => $file['name']
                );

                if ($req->execute($array)) {
                    $req->closecursor();
                    $bdd = null;

                    if (is_array($oldVideo) == false && $file['name'] != $oldVideo && $oldVideo != 'default.mp4') {
                        unlink('src/video/' . $oldVideo);
                    }

                    return array(
                        'result' => true,
                        'text' => 'La video a été mise à jour',
                        'video' => $file['name']
                    );
                }else {
                    $req->closecursor();
                    $bdd = null;

                    if ($file['name'] != 'default.mp4') {
                        unlink('src/video/' . $file['name']);
                    }

                    return array(
                        'result' => false,
                        'text' => 'La video n\'a pas pu être mise à jour'
                    );
                }
            }elseif (strlen($file['name']) > 50) {
                if ($file['name'] != 'default.mp4') {
                    unlink('src/video/' . $file['name']);
                }

                return array(
                    'result' => false,
                    'text' => 'Le titre de la vidéo ne peut faire plus de 50 caractères'
                );
            }else {
                return $upload;
            }
        }

        private function uploadVideo ($file) {
            if (isset($file['name'], $file['type'], $file['tmp_name']) && is_array($file) && $file['name'] != '' && $file['type'] != '' && $file['tmp_name'] != '' && $file['size'] > 0 && $file['size'] <= 67108864 && $file['name'] != 'default.mp4') {
                if ($file['type'] == 'video/mp4') {
                    $type = true;
                }else {
                    $type = false;
                }

                if ($type) {
                    if (move_uploaded_file($file['tmp_name'], 'src/video/' . $file['name'])) {
                        return array(
                            'result' => true,
                            'text' => 'La vidéo a été envoyée'
                        );
                    }else {
                        return array(
                            'result' => false,
                            'text' => 'La vidéo n\'a pas pu être uploader'
                        );
                    }
                }else {
                    return array(
                        'result' => false,
                        'text' => 'Le fichier est dans un format inconnu. Seul le format mp4 est reconnue.',
                    );
                }
            }elseif ($file['name'] == '') {
                return array(
                    'result' => false,
                    'text' => 'Le nom du fichier n\'est pas renseigner'
                );
            }elseif ($file['name'] == 'default.mp4') {
                return array(
                    'result' => false,
                    'text' => 'Le nom du fichier est déja utilisé'
                );
            }elseif ($file['type']) {
                return array(
                    'result' => false,
                    'text' => 'Le type du fichier n\'est pas renseigner'
                );
            }elseif ($file['tmp_name']) {
                return array(
                    'result' => false,
                    'text' => 'Le chemin du fichier n\'est pas renseigner'
                );
            }elseif ($file['size'] == '' || $file['size'] == 0) {
                return array(
                    'result' => false,
                    'text' => 'La taille du fichier n\'est pas renseigner'
                );
            }elseif ($file['size'] > 67108864) {
                return array(
                    'result' => false,
                    'text' => 'La taille du fichier est trop lourd. il ne peut exéder 64 Mo.'
                );
            }else {
                return array(
                    'result' => false,
                    'text' => 'Erreur'
                );
            }
        }

        /// getter ///
        public function titre() {
            $titre = $this->_titre;

            if ($titre == null) {
                $titre = 'titre';
            }

            return $titre;
        }

        public function logo() {
            $logo = $this->_logo;

            if ($logo == null) {
                return false;
            }elseif (file_exists('src/logo/' . $logo)) {
                return $logo;
            }else {
                return false;
            }
        }

        public function phrase() {
            $phrase = $this->_phrase;

            if ($phrase == null) {
                return false;
            }else {
                return $phrase;
            }
        }

        public function video () {
            $video = $this->_video;

            if ($video == null) {
                $videos = array(
                    'webm' => 'default.webm',
                    'ogg' => 'default.ogg',
                    'mp4' => 'default.mp4'
                );
            }else {
                $videos = $this->_video;
            }

            return $videos;
        }

        public function description() {
            $description = $this->_description;

            if ($description == null) {
                return false;
            } else {
                return $description;
            }
        }

        public function address() {
            $numero = $this->_numeroDeRue;
            $rue = $this->_rue;
            $ville = $this->_ville;
            $cp = $this->_cp;

            if ($numero != null && $rue != null && $ville != null && $cp != null) {
                $array = array(
                    'numero' => $numero,
                    'rue' => $rue,
                    'ville' => $ville,
                    'cp' => $cp
                );

                return $array;
            }else {
                return false;
            }
        }

        public function numero() {
            $numero = $this->_telephone;

            if ($numero != null) {
                return '0' . $numero;
            }else {
                return false;
            }
        }
    }
?>