<?php

namespace App\Controller;

use App\Entity\Project;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProjectController extends AbstractController
{
    /**
     * @Route("/projects", name="app_project")
     */
    public function index(): JsonResponse
    {
        /**
         * @Route("/project", name="project_index", methods={"GET"})
         */

        $project = $this->getDoctrine()->getRepository(Project::class)->findAll();

        $data = [];

        foreach ($project as $project) {
           $data[] = [
               'id' => $project->getId(),
               'name' => $project->getName(),
               'description' => $project->getDescription(),
           ];
        }

        return $this->json($data);
    }

    /**
     * @Route("/project/create", name="project_new", methods={"POST"})
     */
    public function new(Request $request): Response
    {

        $em =$this->getDoctrine()->getManager();

        $project = new Project();
        $project->setName($request->request->get('name'));
        $project->setDescription($request->request->get('description'));
        $em->persist($project);
        $em->flush();

        return $this->json('Created new project successfully with id ' . $project->getId());
    }

    /**
     * @Route("/project/{id}", name="get_project", methods ={"GET"})
     */
    public function getProject(int $id){
        $project = $this->getDoctrine()->getRepository(Project::class)->find($id);
        $project = [
            "id"=> $project->getId(),
            "name"=> $project->getName(),
            "description"=> $project->getDescription(),
        ];

        return $this->json($project);
    }


    /**
     * @Route("/project/{id}",name="edit_project",methods={"PUT"})
     */    public function editProject(Request $request,int $id){
        $project = $this->getDoctrine()->getRepository(Project::class)->find($id);
        $em = $this->getDoctrine()->getManager();

        if(!$project){
            return 'not project';
        }

        $project->setName($request->request->get('name'));
        $project->setDescription($request->request->get('description'));
        $em->flush();

        return $this->json('update');
    }


    /**
     * @Route("project/{id}", methods={"DELETE"})
     */
    public function deleteProject(int $id){
        $project = $this->getDoctrine()->getRepository(Project::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($project);

        return $this->json('delete', Response::HTTP_NO_CONTENT);
    }

}
