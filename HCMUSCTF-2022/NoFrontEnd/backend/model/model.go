package model

import "github.com/golang-jwt/jwt"

type Credentials struct {
	Username string `json:"uname"`
	Password string `json:"pwd"`
}
type Claims struct {
	Username string `json:"uname"`
	jwt.StandardClaims
}

type UserPostData struct {
	Key string `json:"key"`
	Data string `json:"data"`
}
