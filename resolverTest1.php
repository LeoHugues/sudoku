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

    $resolver = new Resolver2();

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
class Resolver2 {
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
     * Fonction permettant de tester toutes valeurs possible pour une case
     * et y attribuer une valeurs uniquement si celle-ci est implicite...
     *
     * @param $grille
     */
    function testeCase ($grille)
    {
        $i = $this->position['ligne'];
        $j = 0;
        for($k = 1; $k < 10; $k++) {
            if ($this->absentSurLigne($k,$grille,$i) && $this->absentSurColonne($k,$grille,$j) && $this->absentSurBloc($k,$grille,$i,$j)) {
                $autreEndroitPossible = false;
                foreach ($grille[$i] as $case) { // Pour toutes les cases de la ligne
                    if ($case == 0 && $this->position['colone'] != $j) { // On regarde si c'est une case vierge et pas la position de la case testé
                        if ($this->absentSurLigne($k,$grille,$i) && $this->absentSurColonne($k,$grille,$j) && $this->absentSurBloc($k,$grille,$i,$j) ) { // Et si il n'existe pas d'autres endroits possible pour la valeur
                            $autreEndroitPossible = true;
                        }
                    }
                    $j++;
                }
                $j = 0;
                if ($autreEndroitPossible == false) {// Si la valeur est obligatoirement ici on la note dans la grille
                    echo $this->position['ligne'] . " " . $this->position['colone'] . " => " . $k . "<br>";
                    $grille[$this->position['ligne']][$this->position['colone']] = $k;
                    $this->grilleResolu[$this->position['ligne']][$this->position['colone']] = $k;
                    $this->iteration++;
                }
            }
        }
    }

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
        $this->position['colone'] = 0;
$nbtest = 0;
        while (!$this->grilleComplete($this->grilleResolu)) {
            //$this->iteration++;
            $nbtest++;
            if ($this->iteration > 20) {
                var_dump($this->iteration);var_dump($nbtest);
                $this->affichage($this->grilleResolu);die;
            }
            foreach ($grille as $ligne) {
                foreach ($ligne as $case) {
                    if ($case == 0) {
                        $this->testeCase($this->grilleResolu);
                    }
                    $this->position['colone']++;
                }
                $this->position['colone'] = 0;
                $this->position['ligne']++;
            }
            $this->position['ligne'] = 0;
        }
    }
}
