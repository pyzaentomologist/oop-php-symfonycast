<?php

namespace App\Controller;

use App\Entity\BigFootSighting;
use App\Form\BigfootSightingType;
use App\Model\DebuggableBigFootSightingScore;
use App\Service\DebuggableSightingScorer;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BigFootSightingController extends AbstractController
{
    /**
     * @Route("/sighting/upload", name="app_sighting_upload")
     * @IsGranted("ROLE_USER")
     */
    public function upload(Request $request, DebuggableSightingScorer $sightingScorer, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(BigFootSightingType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var BigFootSighting $sighting */
            $sighting = $form->getData();
            $sighting->setOwner($this->getUser());

            $bfsScore = $sightingScorer->score($sighting);
            $sighting->setScore($bfsScore->getScore());

            $entityManager->persist($sighting);
            $entityManager->flush();

            $this->addFlash('success', 'New BigFoot Sighting created successfully!');

            $this->addFlash('success', sprintf(
                'Btw, the scoring took %f milliseconds',
                $bfsScore->getCalculationTime() * 1000
            ));

            return $this->redirectToRoute('app_sighting_show', [
                'id' => $sighting->getId()
            ]);
        }

        return $this->render('main/sighting_new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/sighting/{id}", name="app_sighting_show")
     */
    public function showSighting(BigFootSighting $bigFootSighting)
    {
        return $this->render('main/sighting_show.html.twig', [
            'sighting' => $bigFootSighting
        ]);
    }
}
