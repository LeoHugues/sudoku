<?php

main();

/**
 * Le programme
 */
function main() {
    $grilleFacile =
        [
            [0,9,0,  0,0,8,  0,0,0],
            [3,1,0,  2,5,0,  8,4,6],
            [4,0,2,  0,0,0,  7,0,9],

            [0,0,0,  8,2,4,  0,3,0],
            [8,3,5,  0,0,0,  4,1,2],
            [0,7,0,  5,1,3,  0,0,0],

            [7,0,1,  0,0,0,  9,0,5],
            [9,4,8,  0,7,5,  0,6,3],
            [0,0,0,  9,0,0,  0,7,0]
        ];
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
    $resolver->affichage($grilleFacile);

    $resolver->resolve($grilleFacile);
    echo "Grille resolue :<br><br>";
    $resolver->affichage($resolver->grilleResolu);

    echo '<br> Nombre d\'iteration pour resoudre la grille : ' . $resolver->iteration;
}


/**
 * Class Resolver
 *
 * classe qui contient toutes les méthodes pour résoudre une grille de sudoku
 */
class Resolver {
    public $grilleResolu = array();
    public $iteration = 0;
    public $position = array();

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
     * Fonction permettant de tester toutes valeurs possible pour une ligne
     * et y attribuer des valeurs uniquement si celles-ci sont implicite...
     *
     * @param $grille
     */
    function testeLigne ($grille)
    {
        $i = $this->position['ligne']; // Ligne en question
        $j = 0;

        for($k = 1; $k < 10; $k++) {// pour toutes les valeurs possibles on va tester si on peut les insérer dans la ligne
            if ($this->absentSurLigne($k,$grille,$i)) {// On regarde si le chiffre manque à la ligne
                $nbEndroitPossible = 0; // On part du principe que le chiffre ne peut être placé nulepart avec la grille actuel
                foreach ($grille[$i] as $case) { // Pour toutes les cases de la ligne
                    if ($case == 0) { // On regarde si c'est une case vierge
                        // Si la valeur est absente, donc autorisée à être notée dans la case
                        if ($this->absentSurLigne($k,$grille,$i) && $this->absentSurColonne($k,$grille,$j) && $this->absentSurBloc($k,$grille,$i,$j)) { // Et si il n'existe pas d'autres endroits possible pour la valeur
                            $nbEndroitPossible++; // On incrémente le nombre d'endroit possible pour ce chiffre sur la ligne
                            $positionPossible = ['ligne' => $i, 'colone' => $j]; // on retient les coordonnées
                        }
                    }
                    $j++;
                }
                $j = 0;
                if ($nbEndroitPossible == 1) {// Si la valeur est obligatoirement ici (un seul endroit possible) on la note dans la grille
                    $grille[$positionPossible['ligne']][$positionPossible['colone']] = $k;
                    $this->grilleResolu[$positionPossible['ligne']][$positionPossible['colone']] = $k;
                }
            }
        }
    }

    /**
     * Vérifie si la grille est complete
     *
     * @param $grille
     * @return bool
     */
    public function grilleComplete($grille) {
        foreach ($grille as $ligne) {
            foreach ($ligne as $case) {
                if ($case == 0) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * @param $grille
     */
    public function resolve($grille) {
        $this->grilleResolu = $grille;
        $this->iteration = 0;
        $this->position['ligne'] = 0;
        //$this->position['colone'] = 0;

        while (!$this->grilleComplete($this->grilleResolu)) { // Tant que la grille n'est pas complète
            $this->iteration++;
            for ($i = 0; $i < 9; $i++) {// On va essayer de remplir toute les lignes...
                $this->testeLigne($this->grilleResolu);
                $this->position['ligne']++;
            }
            $this->position['ligne'] = 0;
        }
    }
}
