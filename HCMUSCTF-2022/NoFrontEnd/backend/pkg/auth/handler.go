package auth

import (
	"net/http"
	"time"

	"hcmus.ctf/challenge/database"
	"hcmus.ctf/challenge/model"
	"hcmus.ctf/challenge/utils"
	"github.com/golang-jwt/jwt"
	"github.com/labstack/echo/v4"
	log "github.com/sirupsen/logrus"
)

type Handler struct {
	DB database.Database
}

var jwtKey = []byte("my_secret_key")

func (h *Handler) Register(c echo.Context) error {
	registerData := model.Credentials{}
	err := c.Bind(&registerData)
	if err != nil {
		log.WithFields(
			log.Fields{
				"error":    err.Error(),
				"location": "register-hanlder",
			},
		).Errorln("error binding json to struct")
		return c.JSON(http.StatusUnprocessableEntity, utils.Response{
			Message: "Cannot register, wrong data",
		})
	}
	if registerData.Username == "" || registerData.Password == "" {
		return c.JSON(http.StatusUnprocessableEntity, utils.Response{
			Message: "Cannot register, wrong data",
		})
	}
	added, err := h.DB.InsertCredentials(registerData)
	if err != nil {
		log.WithFields(
			log.Fields{
				"error":    err.Error(),
				"location": "register-handler",
			},
		).Errorln("error adding to db")
		return c.JSON(http.StatusUnprocessableEntity, utils.Response{
			Message: "Cannot register",
		})
	}
	if !added {
		return c.JSON(http.StatusNotAcceptable, utils.Response{
			Message: "Username is exist",
		})
	}
	return c.JSON(http.StatusCreated, utils.Response{
		Data:    registerData.Username,
		Message: "Register successful",
	})
}

func (h *Handler) Login(c echo.Context) error {
	loginData := model.Credentials{}
	err := c.Bind(&loginData)
	if err != nil {
		log.WithFields(
			log.Fields{
				"error":    err.Error(),
				"location": "login-hanlder",
			},
		).Errorln("error binding json to struct")
		return c.JSON(http.StatusUnprocessableEntity, utils.Response{
			Message: "Cannot login, wrong data",
		})
	}
	if loginData.Username == "" || loginData.Password == "" {
		return c.JSON(http.StatusUnprocessableEntity, utils.Response{
			Message: "Cannot login, wrong data",
		})
	}
	checked, err := h.DB.CheckCredentials(loginData)
	if err != nil {
		log.WithFields(
			log.Fields{
				"error":    err.Error(),
				"location": "register-handler",
			},
		).Errorln("error checking to db")
		return c.JSON(http.StatusUnprocessableEntity, utils.Response{
			Message: "Cannot login",
		})
	}
	if !checked {
		return c.JSON(http.StatusUnauthorized, utils.Response{
			Message: "Username or password not correct",
		})
	}

	expirationTime := time.Now().Add(5 * time.Minute)
	claims := &model.Claims{
		Username: loginData.Username,
		StandardClaims: jwt.StandardClaims{
			ExpiresAt: expirationTime.Unix(),
		},
	}

	token := jwt.NewWithClaims(jwt.SigningMethodHS256, claims)
	tokenString, err := token.SignedString(jwtKey)
	if err != nil {
		return c.JSON(http.StatusInternalServerError, utils.Response{
			Message: "Something wrong!",
		})
	}
	c.SetCookie(&http.Cookie{
		Name:    "token",
		Value:   tokenString,
		Expires: expirationTime,
	})

	return c.JSON(http.StatusCreated, utils.Response{
		Data:    loginData.Username,
		Message: "Login successful",
	})
}
