<?php
// src/Controller/UploadController.php

namespace App\Controller;

use App\Entity\Record;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Mime\MimeTypes;

class UploadController extends AbstractController
{
    #[Route('/api/upload', name: 'api_upload', methods: ['POST'])]
    public function upload(Request $request, EntityManagerInterface $em, #[CurrentUser] $user): Response
    {
        $title = $request->request->get('title');
        $artist = $request->request->get('artist');
        $format = $request->request->get('format');
        $tracknumber = $request->request->get('tracknumber');
        $tracktitle = $request->request->get('tracktitle');
        $tracktime = $request->request->get('tracktime');
        $label = $request->request->get('label');
        $country = $request->request->get('country');
        $releasedate = $request->request->get('releasedate');
        $genre = $request->request->get('genre');
        $collectionname = $request->request->get('collectionname');
        $price = $request->request->get('price');
        $listenlink = $request->request->get('listenlink');
        $grade = $request->request->get('grade');

        // Handle file uploads
        $bookletfront = $request->files->get('bookletfront');
        $bookletback = $request->files->get('bookletback');

        $uploadDir = $this->getParameter('kernel.project_dir') . '/public/uploads';

        $bookletfrontFilename = null;
        $bookletbackFilename = null;

        if ($bookletfront) {
            $mimeTypes = new MimeTypes();
            $extension = $mimeTypes->getExtensions($bookletfront->getMimeType())[0];
            $bookletfrontFilename = md5(uniqid()) . '.' . $extension;
            $bookletfront->move($uploadDir, $bookletfrontFilename);
        }

        if ($bookletback) {
            $mimeTypes = new MimeTypes();
            $extension = $mimeTypes->getExtensions($bookletback->getMimeType())[0];
            $bookletbackFilename = md5(uniqid()) . '.' . $extension;
            $bookletback->move($uploadDir, $bookletbackFilename);
        }

        $record = new Record(
            $user,
            $title,
            $artist,
            $format,
            $tracknumber,
            $tracktitle,
            $tracktime,
            $label,
            $country,
            new \DateTime($releasedate),
            $genre,
            $collectionname,
            (float)$price,
            $bookletfrontFilename,
            $bookletbackFilename,
            $listenlink,
            $grade
        );

        $em->persist($record);
        $em->flush();

        return new Response('Record uploaded successfully', Response::HTTP_CREATED);
    }
}

