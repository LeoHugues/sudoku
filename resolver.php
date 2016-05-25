<?php

main();

/**
 * Le programme
 */
function main() {
    $grilleDifficile =
        [
            [0,9,2,  0,0,0,  0,8,0],
            [0,7,0,  3,0,0,  0,0,0],
            [4,0,3,  6,0,8,  2,7,0],

            [0,4,0,  1,0,0,  0,0,7],
            [0,0,0,  5,0,2,  0,0,0],
            [3,0,0,  0,0,4,  0,5,0],

            [0,1,6,  7,0,3,  5,0,8],
            [0,0,0,  0,0,9,  0,1,0],
            [0,3,0,  0,0,0,  6,9,0]
        ];
    $grilleDiabolique =
        [
            [0,6,0,  4,7,0,  0,0,0],
            [2,0,8,  3,0,0,  0,9,0],
            [0,1,3,  0,0,5,  0,4,0],

            [0,0,0,  0,9,0,  2,0,0],
            [6,0,0,  0,0,0,  0,0,3],
            [0,0,7,  0,1,0,  0,0,0],

            [0,8,0,  1,0,0,  4,7,0],
            [0,3,0,  0,0,7,  8,0,1],
            [0,0,0,  0,4,8,  0,2,0]
        ];

    $grilleDifficilePourResolver =
        [
            [9,0,0,  1,0,0,  0,0,5],
            [0,0,5,  0,9,0,  2,0,1],
            [8,0,0,  0,4,0,  0,0,0],

            [0,0,0,  0,8,0,  0,0,0],
            [0,0,0,  7,0,0,  0,0,0],
            [0,0,0,  0,2,6,  0,0,9],

            [2,0,0,  3,0,0,  0,0,6],
            [0,0,0,  2,0,0,  9,0,0],
            [0,0,1,  9,0,4,  5,7,0]
        ];

    $pireDesGrilles =
        [
            [0,0,0,  0,0,0,  0,0,0],
            [0,0,0,  0,0,3,  0,8,5],
            [0,0,1,  0,2,0,  0,0,0],

            [0,0,0,  5,0,7,  0,0,0],
            [0,0,4,  0,0,0,  1,0,0],
            [0,9,0,  0,0,0,  0,0,0],

            [5,0,0,  0,0,0,  0,7,3],
            [0,0,2,  0,1,0,  0,0,0],
            [0,0,0,  0,4,0,  0,0,9]
        ];

    $resolver = new Resolver();

    echo "Grille a resoudre :<br><br>";
    $resolver->affichage($pireDesGrilles);

    $resolver->resolve($pireDesGrilles);
    echo "Grille resolue :<br><br>";
    $resolver->affichage($resolver->grilleResolu);

    echo '<br> Nombre d\'iteration pour resoudre la grille : ' . $resolver->itération;
}


/**
 * Class Resolver
 *
 * classe qui contient toutes les méthodes pour résoudre une grille de sudoku
 */
class Resolver {
     public $grilleResolu = array();
     public $itération = 0;

    /**
     * Fonction d'affichage
     */
    function affichage ($grille)
    {
        for ($i=0; $i<9; $i++)
        {
            for ($j=0; $j<9; $j++) {
                printf((($j + 1) % 3) ? "%d " : "%d | ", $grille[$i][$j]);
            }
            echo('<br>');
            if (!(($i +1)%3)) {
                echo("-----------------------<br>");
            }
        }
        echo("<br><br>");
    }

    /**
     * Teste la présence d'un chiffre sur une ligne
     *
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
     * Teste la présence d'un chiffre sur une colone
     *
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
     * Teste la présence d'un chiffre dans un bloc
     *
     * @param $k int Nombre recherché
     * @param $grille array grille de sudoku
     * @param $i int position de la ligne
     * @param $i int position de la colone
     * @return bool retourne FAUX si la valeur est trouvée, sinon on retourne VRAI
     */
    function absentSurBloc ($k, $grille, $i, $j)
    {
        // Cela permet de retrouver les coordonnées de la première case du bloc
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

    /**
     * Fonction récursive qui va résoudre le sudoku
     *
     * @param $grille
     * @param $position
     * @return bool
     */
    function estValide ($grille, $position)
    {
        // On incrémente le nombre d'itération à chaque fois que l'on passe dans la fonction
        $this->itération++;

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
                $this->grilleResolu[$i][$j] = $k;

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

    /**
     * @param $grille
     */
    public function resolve($grille) {
        $this->grilleResolu = $grille;
        $this->itération = 0;
        $this->estValide($grille, 0);
    }
}
