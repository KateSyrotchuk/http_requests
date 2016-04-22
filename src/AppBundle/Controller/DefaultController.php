<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use AppBundle\Form\Type\CommentType;
use AppBundle\Form\Type\TimeEntryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="all_projects")
     */
    public function projectsAction()
    {
        $data = $this->get('api_manager')->get('projects.json', 'GET');
        return $this->render('AppBundle::projects.html.twig', array(
            'projects' => $data['projects'],
        ));
    }

    /**
     * @Route("/issues/{project_id}", name="issues_per_project")
     */
    public function projectsIssuesAction($project_id)
    {
        $data = $this->get('api_manager')->get('issues.json?project_id=' . $project_id, 'GET');
        return $this->render('AppBundle::issues_per_project.html.twig', array(
            'issues' => $data['issues'],
        ));
    }

    /**
     * @Route("/new_time_entry/{project_id}", name="new_time_entry")
     */
    public function newTimeEntryAction(Request $request, $project_id)
    {
        $timeEntryForm = $this->createForm(TimeEntryType::class, null, array());
        $timeEntryForm->handleRequest($request);
        if ($timeEntryForm->isValid()) {
            $data = $timeEntryForm->getData();
            $body = array('time_entry' => [
                'hours' => $data['hours'],
                'project_id' => $project_id,
                'activity_id' => 11
            ]);
            $this->get('api_manager')->post('time_entries.json', $body);
            $this->get('session')->getFlashBag()->add('success', 'Setting Time Entry was successfully');
            return $this->redirectToRoute('all_projects');
        }
        return $this->render('@App/time_entry.html.twig', array(
            'form' => $timeEntryForm->createView(),
        ));
    }

    /**
     * @Route("/comments/{project_id}", name="comments_of_project")
     */
    public function commentsAction($project_id)
    {
        $comments = $this->getDoctrine()->getRepository('AppBundle:Comment')->findBy(array('projectId' => $project_id));
        return $this->render("AppBundle::comments.html.twig", array('comments' => $comments, 'project_id' => $project_id));
    }

    /**
     * @Route("/comment_new/{project_id}", name="new_comment")
     * @Method("POST")
     */
    public function commentNewAction(Request $request, $project_id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $comment = new Comment();
        $commentForm = $this->createForm(CommentType::class, $comment);
        $commentForm->handleRequest($request);
        if ($commentForm->isValid() && $commentForm->isSubmitted()) {
            $comment->setProjectId($project_id);
            $entityManager->persist($comment);
            $entityManager->flush();
            return $this->redirectToRoute('comments_of_project', array('project_id' => $project_id));
        }
        return $this->render('@App/new_comment.html.twig', array(
            'form' => $commentForm->createView(),
            'project_id' => $project_id
        ));
    }
}