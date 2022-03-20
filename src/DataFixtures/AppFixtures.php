<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Participant;
use App\Entity\Sortie;
use App\Entity\Ville;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    private ObjectManager $manager;
    private UserPasswordHasherInterface $hasher;
    private Generator $faker;

    // import pour encoder les mots de passes, attention a importer faker/factory
    public function __construct(UserPasswordHasherInterface $passwordHasher)

    {
        $this->faker = Factory::create('fr_FR');
        $this->hasher = $passwordHasher;
    }


    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;
        $this->addEtat();
        $this->addVille();
        $this->addLieu();
        $this->addCampus();
        $this->addParticipant();
        $this->addSortie();
    }

    /**
     * Fonction en charge d'ajouter des etats en base de données
     * etats ajoutés :
     * Créée
     * Ouverte
     * Clôturée
     * Activité en cours
     * Passée
     * Annulée
     *
     * @TODO factoriser le code
     * @return void
     */
    public function addEtat(){

        $etat = new Etat();
        $etat->setLibelle('Créée');
        $this->manager->persist($etat);

        $etat = new Etat();
        $etat->setLibelle('Ouverte');
        $this->manager->persist($etat);

        $etat = new Etat();
        $etat->setLibelle('Clôturée');
        $this->manager->persist($etat);

        $etat = new Etat();
        $etat->setLibelle('Activité en cours');
        $this->manager->persist($etat);

        $etat = new Etat();
        $etat->setLibelle('Passée');
        $this->manager->persist($etat);

        $etat = new Etat();
        $etat->setLibelle('Annulée');
        $this->manager->persist($etat);

        $this->manager->flush();


    }

    /**
     * Fonction en charge d'inserer des lieux en base de données
     *
     * @return void
     */
    public function addLieu(){
        // recuperation listes de villes
        $villes = $this->manager->getRepository(Ville::class)->findAll();
        for ($i = 0; $i < 10; $i++) {
            $lieu = new Lieu();
            $lieu->setNom($this->faker->lastName);
            $lieu->setRue($this->faker->streetName);
            //$ville = new Ville();
            //$ville = $this->manager->getRepository(Ville::class)->find($this->faker->numberBetween(1,3));
            //choix aleatoire de ville dans la liste.
            $lieu->setVille($this->faker->randomElement($villes));
            $this->manager->persist($lieu);
        }

        $this->manager->flush();
    }

    /**
     * Fonction en charge d'ajouter des particpants en base de données
     * Mot de passe par défaut  : 123
     * Actif = true
     *
     * @return void
     */
    public function addParticipant(){

        for ($i = 0; $i < 10; $i++) {

            $participant = new Participant();
            $participant->setNom($this->faker->lastName);
            $participant->setPrenom($this->faker->firstName);
            $participant->setTelephone($this->faker->phoneNumber);
            $participant->setMail($this->faker->email);
            //$participant->setMotPasse($this->hasher->hashPassword($participant, '123'));
            $participant->setMotPasse('123');
            $participant->setActif(true);
            //$participant->setPseudo($this->faker->userName);
            $campus = $this->manager->getRepository(Campus::class)->findAll();
            $participant->setCampus($this->faker->randomElement($campus));

            $this->manager->persist($participant);

            }
        $this->manager->flush();
    }

    /**
     * Fonction en charge d'ajouter de sortie
     * @return void
     */
    public function addSortie(){


        for ($i = 0; $i < 10; $i++){
            $sortie = new Sortie();
            $sortie->setNom($this->faker->name);
            $sortie->setDateHeureDebut($this->faker->dateTime);
            $sortie->setDateLimiteInscription($this->faker->dateTime);
            $sortie->setDuree($this->faker->numberBetween(60,3600));
            $sortie->setNbInscriptionsMax($this->faker->numberBetween(10,100));
            $sortie->setInfosSortie($this->faker->text);

            // pour recuperer l'ensemble des etats en base de données et en attribuer un au hasard
            $etats = $this->manager->getRepository(Etat::class)->findAll();
            $sortie->setEtat($this->faker->randomElement($etats));

            // pour recuperer l'objet personne dont l'id est le chiffre aleatoire
            $participants = $this->manager->getRepository(Participant::class)->findAll();
            $sortie->setOrganisateur($this->faker->randomElement($participants));

            // pour recuperer l'objet lieu dont l'id est le chiffre aleatoire
            $lieux = $this->manager->getRepository(Lieu::class)->findAll();
            $sortie->setLieu($this->faker->randomElement($lieux));

            // pour recuperer l'objet campus dont l'id est le chiffre aleatoire
            $campus = $this->manager->getRepository(Campus::class)->findAll();
            $sortie->setCampus($this->faker->randomElement($campus));


            //ajout de l'organisateur dans la liste des participants
            $sortie->addParticipant($this->faker->randomElement($participants));

            $this->manager->persist($sortie);

        }
        $this->manager->flush();
    }

    /**
     * Fonction en charge de rajoute rdes villes en bases de données
     * ajout de Rennes, Nantes, Niort,
     * @return void
     */
    public function addVille(){

        $liste = array('Nantes'=> 44000, 'Rennes'=> 35000, 'Niort' => 79000);

        foreach ($liste as $i => $value){
            $newCity = new Ville();
            $newCity->setNom($i);
            $newCity->setCodePostal($value);
            $this->manager->persist($newCity);
        }

        $this->manager->flush();

    }

    /**
     * Fonction en charge d'ajouter des campus en base de données
     *
     * @return void
     */
    public function addCampus() {

        $campus = new Campus();
        $campus->setNom('Niort');
        $this->manager->persist($campus);

        $campus = new Campus();
        $campus->setNom('Nantes');
        $this->manager->persist($campus);

        $campus = new Campus();
        $campus->setNom('Rennes');
        $this->manager->persist($campus);


        $this->manager->flush();
    }

}
