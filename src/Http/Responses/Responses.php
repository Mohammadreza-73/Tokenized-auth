<?php

namespace TokenizedLogin\Http\Responses;

use Illuminate\Http\Response;

class Responses
{
    public function blockedUser()
    {
        return response()->json([
            'error' => 'You are blocked'
        ], Response::HTTP_BAD_REQUEST);
    }

    public function tokenSent()
    {
        return response()->json([
            'message' => 'Token was sent.'
        ], Response::HTTP_OK);
    }

    public function userNotFound()
    {
        return response()->json([
            'error' => 'Email Does not exist.'
        ], Response::HTTP_NOT_FOUND);
    }

    public function emailNotValid()
    {
        return response()->json([
            'error' => 'Email not valid.'
        ], response::HTTP_BAD_REQUEST);    
    }

    public function checkUserIsGuest()
    {
        return response()->json([
            'error' => 'You should be guest.'
        ], Response::HTTP_BAD_REQUEST);
    }

    public function loggedIn()
    {
        return response()->json([
            'message' => 'You are logged in.'
        ], Response::HTTP_OK);
    }

    public function tokenNotFound()
    {
        return response()->json([
            'error' => 'Token is not valid.'
        ], Response::HTTP_NOT_FOUND);
    }
}