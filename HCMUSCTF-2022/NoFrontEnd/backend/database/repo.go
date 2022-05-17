package database

import (
	"hcmus.ctf/challenge/model"
	_ "github.com/go-sql-driver/mysql"
)

type Database interface {
	Connect() error

	InsertCredentials(cred model.Credentials) (bool, error)
	CheckCredentials(cred model.Credentials) (bool, error)
	InsertData(username string, userData model.UserPostData) (bool, error)
	GetData(username string, userData model.UserPostData) (string, error)
}
