<?php
/**
 * Created by PhpStorm.
 * User: leo
 * Date: 23/05/2016
 * Time: 18:13
 */

$resolver = new resolver();

$resolver->main();

class resolver {
     public $grilleResolu = array();

    // Fonction d'affichage
    /**
     * @param $grille array
     */
    function affichage ($grille)
    {
        for ($i=0; $i<9; $i++)
        {
            for ($j=0; $j<9; $j++) {
                printf((($j + 1) % 3) ? "%d " : "%d|", $grille[$i][$j]);
            }
            echo('<br>');
            if (!(($i +1)%3)) {
                echo("------------------<br>");
            }
        }
        echo("<br><br>");
    }

    /**
     * @param $k int Nombre recherché
     * @param $grille array grille de sudoku
     * @param $i int position de la ligne
     * @return bool retourne FAUX si la valeur est trouvée, sinon on retourne VRAI
     */
    function absentSurLigne ($k, $grille, $i )
    {
        for ($j=0; $j < 9; $j++)
            if ($grille[$i ][$j] == $k) {
                return false;
            }

        return true;
    }

    /**
     * @param $k int Nombre recherché
     * @param $grille array grille de sudoku
     * @param $j int position de la colone
     * @return bool retourne FAUX si la valeur est trouvée, sinon on retourne VRAI
     */
    function absentSurColonne ($k, $grille, $j)
    {
        for ($i =0; $i  < 9; $i ++) {
            if ($grille[$i ][$j] == $k) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param $k int Nombre recherché
     * @param $grille array grille de sudoku
     * @param $i int position de la ligne
     * @param $i int position de la colone
     * @return bool retourne FAUX si la valeur est trouvée, sinon on retourne VRAI
     */
    function absentSurBloc ($k, $grille, $i, $j)
    {
        // Cela permet de retrouver les coordonnée de la première case du bloc
        $_i = $i-($i%3);
        $_j = $j-($j%3);  // ou encore : _i = 3*(i/3), _j = 3*(j/3);
        for ($i=$_i; $i < $_i+3; $i++) {
            for ($j=$_j; $j < $_j+3; $j++) {
                if ($grille[$i][$j] == $k) {
                    return false;
                }
            }
        }

        return true;
    }

    function estValid($grille) {
        for($i = 0; $i < 8; $i++) {
            for($j = 0; $j < 8;$i ++) {
                if ($grille[$i][$j] == 0) {

                }
            }
        }
    }

    function estValide ($grille, $position)
    {
        // Si on est à la 82e case (on sort du tableau)
        if ($position == 9*9) {
            return true;
        }

        // On récupère les coordonnées de la case
        $i = $position/9;
        $j = $position%9;

        // Si la case n'est pas vide, on passe à la suivante (appel récursif)
        if ($grille[$i][$j] != 0) {
            return $this->estValide($grille, $position+1);
        }

        // Backtracking

        // énumération des valeurs possibles
        for ($k=1; $k <= 9; $k++) {
            // Si la valeur est absente, donc autorisée à être notée dans la case
            if ($this->absentSurLigne($k,$grille,$i) && $this->absentSurColonne($k,$grille,$j) && $this->absentSurBloc($k,$grille,$i,$j)) {
                // On enregistre k dans la grille
                $grille[$i][$j] = $k;
                $this->grilleResolu = $grille;

                // On appelle récursivement la fonction estValide(), pour voir si ce choix est bon par la suite
                if ( $this->estValide ($grille, $position+1) ) {
                    return true; // Si le choix est bon, plus la peine de continuer, on renvoie true :)
                }
            }
        }
        // Tous les chiffres ont été testés, aucun n'est bon, on réinitialise la case
        $grille[$i][$j] = 0;

        // Puis on retourne false :(
        return false;
    }

    function main ()
    {
        $grille =
            [
                [9,0,0,1,0,0,0,0,5],
                [0,0,5,0,9,0,2,0,1],
                [8,0,0,0,4,0,0,0,0],
                [0,0,0,0,8,0,0,0,0],
                [0,0,0,7,0,0,0,0,0],
                [0,0,0,0,2,6,0,0,9],
                [2,0,0,3,0,0,0,0,6],
                [0,0,0,2,0,0,9,0,0],
                [0,0,1,9,0,4,5,7,0]
            ];

        printf("Grille avant <br>");
        $this->affichage($grille);

        $this->estValide($grille,0);

        printf("Grille apres<br>");
        $this->affichage($this->grilleResolu);
    }

}
