<?php

namespace App\DataFixtures;

use App\Entity\Address;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AddressFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $data = $this->getRealAddresses();
        foreach ($data as $address) {
            $object = (new Address())
                ->setCountry($address['country'])
                ->setRegion($address['region'])
                ->setCity($address['city'])
                ->setPostcode($address['postcode'])
                ->setStreet($address['street'])
                ->setLatitude($address['latitude'])
                ->setLongitude($address['longitude']);

            $manager->persist($object);
        }

        $manager->flush();
    }

    private function getFakerAdresses(): array
    {
        $res = [];
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i <= 20; $i++) {
            $res[] = [
                'country' => $faker->country(),
                'region' => $faker->country(),
                'city' => $faker->city(),
                'postcode' => $faker->postcode(),
                'street' => $faker->streetAddress(),
                'latitude' => 48.77250288200734,
                'longitude' => 2.4352159316119213,
            ];
        }

        return $res;
    }

    private function getRealAddresses(): array
    {
        return [
            [
                'street' => '6 Av. du Maréchal Juin',
                'city' => 'Sète',
                'region' => 'Languedoc-Roussillon',
                'postcode' => '34200',
                'country' => 'France',
                'latitude' => 43.4061117888376,
                'longitude' => 3.7025693293801627,
            ],
            [
                'street' => '89 rue de la République',
                'city' => 'Lyon',
                'region' => 'Rhône-Alpes',
                'postcode' => '69002',
                'country' => 'France',
                'latitude' => 45.758793536044436,
                'longitude' => 4.834654361015816,
            ],
            [
                'street' => '6 Rue de Bourgogne',
                'city' => 'Orléans',
                'region' => 'Centre',
                'postcode' => '45000',
                'country' => 'France',
                'latitude' => 47.901552515587866,
                'longitude' => 1.9187687772592203,
            ],
            [
                'street' => '41 Rue de Villiers',
                'city' => 'Poissy',
                'region' => 'Île-de-France',
                'postcode' => '78300',
                'country' => 'France',
                'latitude' => 48.923385150862906,
                'longitude' => 2.032159116336884,
            ],
            [
                'street' => '63 Rue de l\'Epineuil',
                'city' => 'Saintes',
                'region' => 'Poitou-Charentes',
                'postcode' => '17100',
                'country' => 'France',
                'latitude' => 45.73714613945203,
                'longitude' => -0.624027838993169,
            ],
            [
                'street' => '55 Rue Saint-Roch',
                'city' => 'Bastia',
                'region' => 'Corse',
                'postcode' => '20200',
                'country' => 'France',
                'latitude' => 42.69886640533982,
                'longitude' => 9.44969724444227,
            ],
            [
                'street' => '48 rue René Descartes',
                'city' => 'Strasbourg',
                'region' => 'Alsace',
                'postcode' => '67000',
                'country' => 'France',
                'latitude' => 48.58002545800433,
                'longitude' => 7.764377246869511,
            ],
            [
                'street' => '4 Rue de l\'Entente',
                'city' => 'Athis-mons',
                'region' => 'Île-de-France',
                'postcode' => '91200',
                'country' => 'France',
                'latitude' => 48.70560223684846,
                'longitude' => 2.362543754878404,
            ],
            [
                'street' => '23 avenue de Provence',
                'city' => 'Vandoeuvre-lès-nancy',
                'region' => 'Lorraine',
                'postcode' => '54500',
                'country' => 'France',
                'latitude' => 48.663103680297624,
                'longitude' => 6.164946146905464,
            ],
            [
                'street' => '71 rue Michel Ange',
                'city' => 'Le Havre',
                'region' => 'Haute-Normandie',
                'postcode' => '76600',
                'country' => 'France',
                'latitude' => 49.50466333368674,
                'longitude' => 0.11823079329563152,
            ],
            [
                'street' => '55 Rue du Dirigeable',
                'city' => 'Aubagne',
                'region' => 'Provence-Alpes-Côte d\'Azur',
                'postcode' => '13400',
                'country' => 'France',
                'latitude' => 43.284610285872645,
                'longitude' => 5.605343113991016,
            ],
            [
                'street' => '35 Rue de la Verrerie',
                'city' => 'Mérignac',
                'region' => 'Aquitaine',
                'postcode' => '33700',
                'country' => 'France',
                'latitude' => 44.833947680931665,
                'longitude' => -0.6267068547048688,
            ],
            [
                'street' => '47 Av. Jean Jaurès',
                'city' => 'Clamart',
                'region' => 'Île-de-France',
                'postcode' => '92140',
                'country' => 'France',
                'latitude' => 48.804052853521476,
                'longitude' => 2.2657707543532903,
            ],
            [
                'street' => '12 Rue du Pré Botté',
                'city' => 'Rennes',
                'region' => 'Bretagne',
                'postcode' => '35000',
                'country' => 'France',
                'latitude' => 48.10992424241505,
                'longitude' => -1.6790249686742253,
            ],
            [
                'street' => '17 Rue de Paris',
                'city' => 'Compiègne',
                'region' => 'Picardie',
                'postcode' => '60200',
                'country' => 'France',
                'latitude' => 49.41599197582174,
                'longitude' => 2.822537447233845,
            ],
        ];
    }
}