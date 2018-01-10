<?php
/**
 * Created by PhpStorm.
 * User: kirill
 * Date: 08.01.2018
 * Time: 7:02
 */

namespace AppBundle\Controller\Admin;
use AppBundle\Entity\Answers;
use AppBundle\Entity\Questions;
use AppBundle\Entity\Questions_quizzes;
use AppBundle\Entity\Quizzes;
use AppBundle\Entity\Users_quizzes;



use AppBundle\Form\QuestionFormType;
use AppBundle\Form\QuizFormType;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Security("is_granted('ROLE_ADMIN')")
 */

class AdminController extends Controller
{
/**
 * @Route("/admin", name="admin")
 */
 public function adminAction(){
     return $this->render('/admin/panel.html.twig');
 }
    /**
     * @Route("/admin/quizzes", name="admin_quizzes_list")
     */
    public function showQuizzes(Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $queryBuilder = $em->getRepository('AppBundle:Quizzes')->createQueryBuilder('bp');

        $query = $queryBuilder->getQuery();

        $paginator = $this->get('knp_paginator');

        $blogPosts = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            $request->query->getInt('limit', 5)/*limit per page*/
        );

        return $this->render('admin/list.html.twig', [
            'blog_posts' => $blogPosts,
        ]);
    }
    /**
     * @Route("/quiz/new", name="add_quiz")
     */
    public function addQuizAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $q = $em->getRepository('AppBundle:Questions')
            ->findAll();
        $data = array();
        for ($i = 0; $i < count($q); $i++) {
            $data[] = array(
                'id' => $q[$i]->getId(),
                'text' => $q[$i]->getName(),
            );
        }


        if ($request->isXMLHttpRequest()) {
            $content = $request->getContent();
            if (!empty($content)) {
                $arr = explode('&', $content);
                $name = explode('=', $arr[0]);
                $status = explode('=', $arr[1]);
                $quiz = new Quizzes();
                $quiz->setName($name[1]);
                $quiz->setDate(new \DateTime("now"));
                $quiz->setnumber_of_players(0);
                $quiz->setStatus($status[1]);
                $em->persist($quiz);
                $em->flush();
                $quiz = $em->getRepository(Quizzes::class)->findOneBy(['name' => $name[1]]);

                for ($i = 2; $i < count($arr); $i++) {
                    $temp = explode('=', $arr[$i]);
                    $question = $em->getRepository(Questions::class)->findOneBy(['id' => (int)$temp[1]]);

                    $q_q = new Questions_quizzes();
                    $q_q->setQuizzes_id($quiz);
                    $q_q->setQuestions_id($question);
                    $em->persist($q_q);
                    $em->flush();
                }
            }
            $this->addFlash(
                'success',
                sprintf('quiz created by you: %s!', $this->getUser()->getEmail())
            );
           return new JsonResponse(json_encode('ok'));
        }
        return $this->render('victorins/quiz/new.html.twig', [
            'data' => $data,

        ]);


    }

    /**
     * @Route("/quiz/{id}/edit/questions", name="edit_quiz_questions")
     */
    public function editQuestionsInQuizAction($id, Request $request)
    {

        $data = array();
        $idOfSelected = array();
        $em = $this->getDoctrine()->getManager();
        $q = $em->getRepository('AppBundle:Questions')
            ->findAll();

        $quiz = $em->getRepository('AppBundle:Quizzes')->findOneBy(['id' => $id]);
        $questions_quizzes = $em->getRepository('AppBundle:Questions_quizzes')->findBy(['quizzes' => $quiz]);
        for ($i = 0; $i < count($questions_quizzes); $i++) {
            $idOfSelected[] = $questions_quizzes[$i]->getQuestions_id()->getId();
        }
        for ($i = 0; $i < count($q); $i++) {
            $question_id = $q[$i]->getId();
            $temp = array(
                'id' => $question_id,
                'text' => $q[$i]->getName(),

            );
            if (in_array($question_id, $idOfSelected)) $temp['selected'] = 'true';
            else $temp['selected'] = 'false';
            $data[] = $temp;
        }

        if ($request->isXMLHttpRequest()) {
            $content = $request->getContent();
            if (!empty($content)) {
                $arr = explode('&', $content);

                $idOfSelectedNew = array();
                for ($i = 0; $i < count($arr); $i++) {
                    $temp = explode('=', $arr[$i]);
                    $idOfSelectedNew[] = (int)$temp[1];
                    $quiz = $em->getRepository('AppBundle:Quizzes')->findOneBy(['id' => $id]);
                    if (!in_array((int)$temp[1], $idOfSelected)) {
                        $question = $em->getRepository(Questions::class)->findOneBy(['id' => (int)$temp[1]]);

                        $q_q = new Questions_quizzes();
                        $q_q->setQuizzes_id($quiz);
                        $q_q->setQuestions_id($question);
                        $em->persist($q_q);
                        $em->flush();
                    }
                }
                $this->addFlash(
                    'success',
                    sprintf('quiz update by you: %s!', $this->getUser()->getEmail())
                );

            }
                return new JsonResponse(json_encode('ok'));
        }
        return $this->render('victorins/quiz/edit_questions.html.twig', [
            'id' => $id,
            'data' => $data
        ]);

    }

    /**
     * @Route("/quiz/{id}/edit", name="edit_quiz")
     */
    public function editQuizAction(Request $request, Quizzes $quiz, $id)
    {
        $form = $this->createForm(QuizFormType::class, $quiz);
        // only handles data on POST
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $quiz = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($quiz);
            $em->flush();
            $this->addFlash('success', 'Quiz updated!');
            return $this->redirectToRoute('admin_quizzes_list');
        }
        return $this->render('victorins/quiz/edit.html.twig', [
            'quizForm' => $form->createView(),
            'id' => $id
        ]);
    }


    /**
     * @Route("/questions/new", name="add_question")
     */
    public function addQuestionAction(Request $request)
    {
        if ($request->isXMLHttpRequest()) {
            $content = $request->getContent();
            if (!empty($content)) {
                $em = $this->getDoctrine()->getManager();

                $arr=explode('&', $content);
                sort($arr);
                $length=count($arr);
                $numOfRight=explode('=', $arr[$length-1])[1];
                $name=explode('=', $arr[$length-2])[1];
                $question= new Questions();
                $question->setName($name);
                $em->persist($question);
                $em->flush();

                $questionId= $em->getRepository(Questions::class)->findOneBy(['name'=>$name])->getId();
                for ($i=0; $i<$length-2; $i++){
                    $answerInfo=explode('=', $arr[$i]);
                    $answer= new Answers();
                    $answer->setText($answerInfo[1]);
                    $answer->setQuestions_id($questionId);
                    if(substr($answerInfo[0], 6)==$numOfRight) $answer->setType(true);
                    else  $answer->setType(false);
                    $em->persist($answer);
                    $em->flush();
                }
                $this->addFlash('success', 'question created!');
            }
            return new JsonResponse(json_encode('ok'));
        }

        return $this->render('victorins/questions/new.html.twig', [


        ]);
    }
    /**
     * @Route("/quiz/{id}/delete", name="delete_quiz")
     *
     */
    public function deleteQuizzAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $quiz = $em->getRepository(Quizzes::class)->findOneBy(['id'=> $id]);


        $q_q = $em->getRepository(Questions_quizzes::class)->findBy(['quizzes'=> $quiz]);
        if(count($q_q)!=0) {
            for ($i = 0; $i < count($q_q); $i++) {
                $em->remove($q_q[$i]);

            }
        }
         $em->remove($quiz);
        $em->flush();
        return $this->render('admin/list.html.twig');
    }

}