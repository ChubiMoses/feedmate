<?php

use App\Http\Controllers\AnswerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\chatController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\groupController;
use App\Http\Controllers\chatIdController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\groupChatController;
use App\Http\Controllers\groupMemberController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\MessageOpenedController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\QuestionLikeController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::get('/example/{api_token}/{name}', function (Request $request) {
//     return response()->json([
//         'name' => $request->name,
//     ]);
// })->middleware('api_token');



// //protocols Routes
// Route::group(['middleware' => ['auth:sanctum']], function(){
    
     
    //User
    Route::post('/user/{apikey}/{authId}', [UserController::class, 'register']);
    Route::get('/user/{apikey}/{authId}', [UserController::class, 'getUserInfo']);
    Route::get('/user/{apikey}/{userId}/info', [UserController::class, 'userInfo']);
    Route::put('/user/{apikey}/{userId}', [UserController::class, 'updateInfo']);
    Route::put('/user/{apikey}/{userId}/lastVisit', [UserController::class, 'lastVisited']);
    Route::get('/users/{apikey}', [UserController::class, 'users']);
    Route::get('/users/{apikey}/{query}/search', [UserController::class, 'search']);

    //Questions
    Route::get('/question/{apikey}/{authId}/{query}/search', [QuestionController::class, 'search']);
    Route::get('/question/{apikey}/{authId}', [QuestionController::class, 'getQuestions']);
    Route::post('/question/{apikey}/{userId}', [QuestionController::class, 'createQuestion']);
    Route::get('/question/{apikey}/{authId}/{userId}', [QuestionController::class, 'myQuestions']);
    Route::get('/question/{apikey}/{authId}/{postId}/post', [QuestionController::class, 'singleQuestion']);
    Route::put('/question/{apikey}/{postId}', [QuestionController::class, 'update']);
    Route::delete('/question/{apikey}/{postId}', [QuestionController::class, 'destroy']);

    //notifications
    Route::get('/notification/{apikey}/{userId}', [NotificationController::class, 'getNotifications']);
    Route::get('/notification/{apikey}/{userId}/count', [NotificationController::class, 'newNotifications']);
    Route::post('/notification/{apikey}', [NotificationController::class, 'createDeleteNotification']);
    Route::put('/notification/{apikey}/{userId}', [NotificationController::class, 'updateNotification']);
    
    //Posts
     Route::get('/post/{apikey}/{authId}/{query}/search', [PostController::class, 'search']);
     Route::get('/post/{apikey}/{authId}', [PostController::class, 'getPosts']);//all post
     Route::post('/post/{apikey}/{userId}', [PostController::class, 'createPost']);//create post
     Route::get('/post/{apikey}/{authId}/{userId}', [PostController::class, 'myPosts']);//get user posts
     Route::get('/post/{apikey}/{authId}/{postId}/post', [PostController::class, 'singlePost']);//get single post
     Route::put('/post/{apikey}/{postId}', [PostController::class, 'update']);//update post
     Route::delete('/post/{apikey}/{postId}', [PostController::class, 'destroy']);//delete post
 
    
     //Comments
     Route::get('/comment/{apiKey}/{authId}/{postId}', [CommentController::class, 'getComments']);//all comments
     Route::post('/comment/{apiKey}/{userId}/{postId}', [CommentController::class, 'saveComment']);//create comment
     Route::put('/comment/{apikey}/{commentId}', [CommentController::class, 'update']);//update comment
     Route::delete('/comment/{apiKey}/{commentId}', [CommentController::class, 'destroy']);//delete comment
 
 
     //likes
     Route::post('/post/{apiKey}/{authId}/{userId}/{postId}', [LikeController::class, 'PostLikeOrUnlike']);//post like
     Route::post('/comment/{apiKey}/{authId}/{userId}/{commentId}', [LikeController::class, 'CommentLikeOrUnlike']);//comment like
 
    //reports
    Route::get('/reports/{apikey}', [ReportController::class, 'getReports']);
    Route::post('/reports/{apikey}', [ReportController::class, 'createReport']);

    //Answers
    Route::get('/answer/{apiKey}/{authId}/{questionId}', [AnswerController::class, 'getAnswers']);
    Route::post('/answer/{apiKey}/{userId}/{questionId}', [AnswerController::class, 'saveAnswer']);
    Route::put('/answer/{apikey}/{answerId}', [AnswerController::class, 'update']);
    Route::delete('/answer/{apiKey}/{answerId}', [AnswerController::class, 'destroy']);

    //likes
    Route::post('/question/{apiKey}/{authId}/{userId}/{questionId}', [QuestionLikeController::class, 'questionLikeOrUnlike']);
    Route::post('/answer/{apiKey}/{authId}/{userId}/{answerId}', [QuestionLikeController::class, 'answerLikeOrUnlike']);

    //follow
    Route::get('/follow/{apikey}/{id}/followers', [FollowController::class, 'followers']);
    Route::get('/follow/{apikey}/{id}/following', [FollowController::class, 'following']);
    Route::post('/follow/{apikey}', [FollowController::class, 'followUnfollow']);

     //Group chats
     Route::get('/groupchat/{apikey}/{id}', [groupChatController::class, 'getChats']);
     Route::get('/groupchat/{apikey}/{id}/last', [groupChatController::class, 'getlastMessage']);
     Route::get('/groupchat/{apiKey}/{groupId}/{userId}/count', [groupChatController::class, 'countMessage']);
     Route::get('/groupchat/{apiKey}/{userId}/count', [groupChatController::class, 'messageCount']);
     Route::post('/groupchat/{apikey}/{id}', [groupChatController::class, 'createChat']);
     Route::delete('/groupchat/{apikey}/{id}', [groupChatController::class, 'deleteChat']);

     //chats
     Route::get('/chat/{apikey}/{id}', [chatController::class, 'getChats']);
     Route::get('/chat/{apikey}/{id}/last', [chatController::class, 'getlastChat']);
     Route::get('/chat/{apiKey}/{userId}/count', [chatController::class, 'messageCount']);
     Route::get('/chat/{apiKey}/{chatId}/{userId}/count', [chatController::class, 'countMessage']);
     Route::post('/chat/{apikey}/{id}', [chatController::class, 'createChat']);
     Route::get('/chat/{apikey}/{id}/seen', [chatController::class, 'updateSeen']);
     Route::delete('/chat/{apikey}/{id}', [chatController::class, 'deleteChat']);

     //message opened/read messages
     Route::get('/message/{apiKey}/{userId}/chat', [MessageOpenedController::class, 'myOpenedMessages']);
     Route::get('/message/{apiKey}/{userId}/group', [MessageOpenedController::class, 'myOpenedgroupMessages']);
     Route::get('/message/{apiKey}/{chatId}/{userId}/chat', [MessageOpenedController::class, 'openedMessages']);
     Route::get('/message/{apiKey}/{groupId}/{userId}/group', [MessageOpenedController::class, 'openedgroupMessages']);
     Route::post('/message/{apiKey}/{chatId}/{messageId}/{userId}/chat', [MessageOpenedController::class, 'messageOpened']);
     Route::post('/message/{apiKey}/{groupId}/{messageId}/{userId}/group', [MessageOpenedController::class, 'groupMessageOpened']);

     //chats id
     Route::get('/chatId/{apikey}/{id}', [chatIdController::class, 'getChatsId']);
     Route::post('/chatId/{apikey}/{id}', [chatIdController::class, 'createChatId']);
     Route::put('/chatId/{apiKey}/{chatId}/{timestamp}', [chatIdController::class, 'updateChatId']);
     Route::delete('/chatId/{apikey}', [chatIdController::class, 'deleteChatId']);

    //Group 
    Route::get('/group/{apikey}', [groupController::class, 'getGroups']);
    Route::get('/group/{apikey}/{id}', [groupController::class, 'groupInfo']);
    Route::post('/group/{apikey}', [groupController::class, 'createGroup']);
    Route::put('/group/{apikey}/{id}', [groupController::class, 'editGroup']);
    Route::delete('/group/{apikey}/{groupId}/{userId}', [groupController::class, 'deleteGroup']);
    Route::delete('/group/{apikey}/{id}/lock', [groupController::class, 'lockUnlockGroup']);
    Route::get('/group/{apikey}/{query}/search', [groupController::class, 'search']);

    //Group member
    Route::get('/groupmember/{apikey}/{id}', [groupMemberController::class, 'getGroupMembers']);
    Route::post('/groupmember/{apikey}/{id}/detail', [groupMemberController::class, 'memberDetail']);
    Route::post('/groupmember/{apikey}/{id}', [groupMemberController::class, 'getMyGroups']);
    Route::put('/groupmember/{apikey}/{id}/{userId}', [groupMemberController::class, 'joinGroup']);
    Route::delete('/groupmember/{apikey}/{id}', [groupMemberController::class, 'leaveGroup']);
    Route::delete('/groupmember/{apikey}/{id}/block', [groupMemberController::class, 'blockUser']);
    
    //Courses
    Route::get('/course/{apikey}', [CourseController::class, 'getCourses']);
    Route::get('/course/{apikey}/{query}', [CourseController::class, 'search']);
    Route::post('/course/{apikey}', [CourseController::class, 'createCourse']);
    
    //Schools
    Route::get('/school/{apikey}', [SchoolController::class, 'getSchools']);
    Route::get('/school/{apikey}/{query}', [SchoolController::class, 'search']);
    Route::post('/school/{apikey}', [SchoolController::class, 'createSchool']);

    //Documents
    Route::get('/document/{apikey}/{authId}', [DocumentController::class, 'getDocuments']);
    Route::get('/document/{apikey}/{authId}/{userId}', [DocumentController::class, 'getMyDocuments']);
    Route::get('/document/{apikey}/{authId}/new/uploads', [DocumentController::class, 'getNewUploads']);
    Route::post('/document/{apikey}/{authId}/{query}', [DocumentController::class, 'search']);
    Route::post('/document/{apikey}/verify', [DocumentController::class, 'verify']);
    Route::post('/document/{apikey}/{userId}', [DocumentController::class, 'saveDocument']);
    Route::put('/document/{apikey}/{docId}', [DocumentController::class, 'update']);
    Route::delete('/document/{apikey}/{docId}', [DocumentController::class, 'delete']);
    Route::get('/document/{apiKey}/{authId}/{userId}/{docId}', [DocumentController::class, 'DocLikeOrUnlike']);//post like

// });