<?php
/**
 * Created by PhpStorm.
 * User: kirill
 * Date: 02.01.2018
 * Time: 6:29
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Answers;
use AppBundle\Entity\Questions;
use AppBundle\Entity\Questions_quizzes;
use AppBundle\Entity\Quizzes;
use AppBundle\Entity\Users_quizzes;



use AppBundle\Form\QuestionFormType;
use AppBundle\Form\QuizFormType;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Security("is_granted('ROLE_USER')")
 */
class IndexController extends Controller
{
    /**
     * @Route("/quizzes", name="quizzes_list")
     */
    public function showQuizzes(Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $queryBuilder = $em->getRepository('AppBundle:Quizzes')->createQueryBuilder('bp')
            ->andWhere('bp.status=:status')
            ->setParameter('status', true);

        $query = $queryBuilder->getQuery();

        $paginator = $this->get('knp_paginator');

        $blogPosts = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            $request->query->getInt('limit', 5)/*limit per page*/
        );

        return $this->render('victorins/list.html.twig', [
            'blog_posts' => $blogPosts,
        ]);
    }

    /**
     * @Route("/quizzes/{id}", name="play_quiz")
     */
    public function playQuiz($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $quiz = $em->getRepository('AppBundle:Quizzes')

            ->findOneBy(['id' => $id]);
        $questions = $em->getRepository('AppBundle:Questions_quizzes')->createQueryBuilder('q')
            ->andWhere('q.quizzes =:id')
            ->setParameter('id', $quiz->getId())
            ->getQuery()
            ->getResult();
        $user = $this->getUser();
        $isPlay= $em->getRepository(Users_quizzes::class)->createQueryBuilder('q')
            ->andWhere('q.users=:user')
            ->andWhere('q.quizzes=:quiz')
            ->setParameter('quiz', $quiz)
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
        if(count($isPlay)!=0){
            if($isPlay[0]->getisComplete()===true)
            {
                $this->addFlash('success', 'You already play  this quiz, you can look Top');
                return $this->redirectToRoute('quizzes_list');
            }
        }

        $results = $em->getRepository(Users_quizzes::class)->createQueryBuilder('a')
            ->andWhere('a.quizzes=:quiz')
            ->andWhere('a.users=:user')
            ->setParameter('user', $user)
            ->setParameter('quiz', $quiz)
            ->getQuery()
            ->getResult();
        $isComplete = false;
        if ($results) {
            $iterator = $results[0]->getDoneAnswers();
            $isComplete = $results[0]->getIsComplete();
        } else $iterator = 0;
        if ($isComplete) {
            return $this->redirectToRoute('quizzes_top', array('id' => 1));
        }

        $rightAnswers = array();
        $textOfQuestion = array();
        for ($i = 0; $i < count($questions); $i++) {
            $tempId = $questions[$i]->getQuestions_id()->getId();
            $question = $questions[$i]->getQuestions_id()->getName();
            $answer = $em->getRepository('AppBundle:Answers')->createQueryBuilder('a')
                ->andWhere('a.questions_id = :id')
                ->setParameter('id', $tempId)
                ->getQuery()
                ->getResult();
            $rightAnswer = $em->getRepository('AppBundle:Answers')->createQueryBuilder('a')
                ->andWhere('a.questions_id= :id')
                ->andWhere('a.type= :type')
                ->setParameter('id', $tempId)
                ->setParameter('type', true)
                ->getQuery()
                ->getResult();
            $textOfQuestion[] = $question;
            $answers[$question] = $answer;

            $rightAnswers[] = $rightAnswer[0]->getText();

        }

        if ($request->isXMLHttpRequest()) {
            $content = $request->getContent();
            if (!empty($content)) {
                $arr = explode('&', $content);
                $numOfQuestion = $arr[count($arr) - 1];
                $temp = explode('=', $arr[0]);
                $isTrue = $rightAnswers[$numOfQuestion - 1] === $temp[1];


                if (!$results) {
                    $new = new Users_quizzes();
                    $new->setQuizzes_id($quiz);
                    $new->setUser_id($user);
                    $new->setDoneAnswers(1);
                    if ($isTrue) $new->setRightAnswers(1);
                    else $new->setRightAnswers(0);
                    $new->setIsComplete(false);
                    $em->persist($new);
                    $em->flush();
                } else {
                    $results = $em->getRepository(Users_quizzes::class)->createQueryBuilder('a')
                        ->andWhere('a.quizzes=:quiz')
                        ->andWhere('a.users=:user')
                        ->setParameter('user', $user)
                        ->setParameter('quiz', $quiz)
                        ->getQuery()
                        ->getResult();
                    if ($isTrue) {
                        $n1 = $results[0]->getRightAnswers();
                        $n1++;
                        $results[0]->setRightAnswers($n1);
                    }
                    $results[0]->setDoneAnswers($numOfQuestion);
                    if (count($textOfQuestion) == $numOfQuestion) {
                        $results[0]->setIsComplete(true);
                        $num=$quiz->getnumber_of_players();
                        $num++;
                        $quiz->setnumber_of_players($num);
                        $em->persist($quiz);
                        $em->flush();
                    }
                    $em->persist($results[0]);
                    $em->flush();
                }
            }
            return new JsonResponse(json_encode($isTrue));
        }
        return $this->render('victorins/game.html.twig', array(
            'quiz' => $quiz,
            'questions' => $textOfQuestion,
            'answers' => $answers,
            'i' => $iterator
        ));


    }

    /**
     * @Route("/quizzes/top/{id}", name="quizzes_top")
     */
    public function showTop($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $quiz = $em->getRepository('AppBundle:Quizzes')
            ->findOneBy(['id' => $id]);
        $users = $em->getRepository('AppBundle:Users_quizzes')
            ->findBy(array('quizzes' => $quiz), array('right_answers' => 'DESC'), 3);
$data=array();
$ourUser=[];
$ourUserEmail=$this->getUser()->getEmail();
for($i=0; $i<count($users); $i++)
{
    $email=$users[$i]->getUser_id()->getEmail();
    $rightAnswers=$users[$i]->getRightAnswers();
    $id=$i+1;
    if($email==$ourUserEmail && $id>3){
        $ourUser=['id'=> $id,
            'email'=>$email,
            'rightAnswers'=>$rightAnswers];
    }
    $data[]=array(
        'id'=> $id,
        'email'=>$email,
        'rightAnswers'=>$rightAnswers
    );



}
if(count($ourUser)!=0) {
    $data[] = $ourUser;
    $finalDate = array_slice($data, 0, 4);
}
else $finalDate = array_slice($data, 0, 3);

        return $this->render('victorins/top.html.twig', array(
            'data' => $finalDate
        ));
    }


}