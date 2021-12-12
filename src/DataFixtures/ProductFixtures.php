<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $colors = ["Rouge", "Blanc", "Noir", "Bleu", "Gris", "Rose", "Jaune"];
        $memories = [1, 2, 3, 4, 5, 6, 7];
        $storages = [64, 128, 256];

        $products = [
            [
                "name" => "Apple iPhone 11 Pro Max",
                "price" => 1149,
                "description" => "Cet iPhone cuvée 2019 offre plus de nouveautés qu'il n'y parait. L'autonomie d'abord, tout bonnement impressionnante pour un smarpthone griffée d'une pomme avec plus de deux jours et demi loin d'une prise. Tout simplement l'un des smartphones les plus autonomes du marché. Les aptitudes photo/vidéo ensuite qui boxent voire dépassent les meilleurs photophone sous Android. Avec l'iPhone 11 Pro Max, Apple retrouve sa place sur le podium des meilleurs smartphones premium sur cette fin 2019. L'iPhone le plus abouti que l'on ait testé depuis bien longtemps",
                "year" => 2018
            ],
            [
                "name" => "Samsung Galaxy Note 10+",
                "price" => 785,
                "description" => "Le Samsung Galaxy Note 10+ permet à la firme sud-coréenne de différencier de nouveau ses gammes Note et S. Bien pensée du début à la fin, de l'écran au stylet, la phablette XXL de la firme sud-coréenne est un plaisir à utiliser au quotidien et offre une expérience ultra-immersive. Techniquement irréprochable, le Samsung Galaxy Note 10+ brille par sa fluidité. Côté autonomie, il tient la route avec une batterie plus puissante qui gère correctement toute cette débauche d'énergie. On regrette l'absence de port mini-jack comme sur les Samsung Galaxy S10 mais cela est compensé par le reste de la fiche technique premium du flagship de Samsung. Le Galaxy Note 10+ est incontestablement le smartphone le plus abouti de la marque.",
                "year" => 2019
            ],
            [
                "name" => "Huawei P30 Pro",
                "price" => 489,
                "description" => "Comme ses prédécesseurs de la série P, le P30 Pro mise principalement sur la photo. Cette année, la grande innovation de Huawei est l'intégration d'un capteur photo correspondant à un zoom optique 5x qui offre des résultats franchement impressionnants. Pour parfaire le tableau, cela permet d'obtenir un grossissement hybride 10x d'une qualité inégalée. La partie photo est LA grande réussite de ce smartphone. En revanche, elle ne se fait pas sans contrepartie notamment au niveau du poids du produit et du design qui n'est pas exceptionnel. Il profite tout de même d'un bel écran OLED incurvé de 6,4 pouces, d'une autonomie sensiblement supérieure à la moyenne et d'un capteur d'empreinte sous l'écran.",
                "year" => 2019
            ],
            [
                "name" => "Xiaomi Mi 9T Pro",
                "price" => 408,
                "description" => "Le Xiaomi Mi 9T Pro est un Mi 9T dopé au Snapdragon 855. Il reprend l'essentiel de la fiche technique de son petit frère ce qui en fait un très bon smartphone qui est en plus vendu à un prix particulièrement agressif. Un écran AMOLED full borderless de qualité, des performances de haute volée, une autonomie confortable, le smartphone de Xiaomi livre une excellente prestation.",
                "year" => 2019
            ],
            [
                "name" => "Samsung Galaxy A50",
                "price" => 265,
                "description" => "Un grand écran AMOLED, un triple capteur photo qui fait le job et une belle autonomie le Samsung Galaxy A50 a de sérieux arguments qui lui permettent d'être l'une des références sur le segment très concurrencé du milieu de gamme. Commercialisé autour des 349€, Samsung tient là sa réponse aux smartphones chinois portés par Xiaomi et autres Pocophone. Le Galaxy A50 n'est pas parfait pour autant. Son capteur ultra grand-angle, pour commencer, a de nombreuses lacunes de nuit. Si les performances du Samsung Galaxy A50, elles ne permettent pas de profiter d'un multitâche poussé. Enfin, on regrette que le lecteur d'empreintes ne soit pas plus réactif que ça.",
                "year" => 2019
            ],
            [
                "name" => "Xiaomi Redmi Note 8T",
                "price" => 153,
                "description" => "S'il ne prend pas vraiment de risques, le Xiaomi Redmi Note 8T permet à la firme chinoise de proposer une référence de plus dans son catalogue. Digne remplaçant du Redmi Note 7, il coche toutes les cases et fait preuve d'une belle polyvalence. On lui reprochera toutefois son manque d'originalité visible notamment au niveau de son design, quasiment inchangé par rapport à la génération précédente. Son interface est aussi loin d'être agréable à parcourir puisque trop chargée.",
                "year" => 2017
            ],
        ];

        foreach ($products as $item) {
            $memory = array_rand($memories, 1);
            $storage = array_rand($storages, 1);
            $colorsProduct = "";

            $product = new Product();
            $product->setName($item['name']);
            $product->setPrice($item['price']);
            $product->setDescription($item['description']);
            $product->setYear($item['year']);
            for ($i=0; $i < rand(1, 6); $i++) {
                $colorsProduct .= $colors[$i] . ",";
            }
            $colorsProduct = substr($colorsProduct, -1) === "," ? substr($colorsProduct, 0, -1) : $colorsProduct;
            $product->setColor($colorsProduct);
            $product->setMemory($memories[$memory]);
            $product->setStorage($storages[$storage]);
            $manager->persist($product);
        }

        $manager->flush();
    }
}
