package middleware

import (
	"net/http"

	"hcmus.ctf/challenge/model"
	"hcmus.ctf/challenge/utils"
	"github.com/golang-jwt/jwt"
	"github.com/labstack/echo/v4"
	log "github.com/sirupsen/logrus"
)

var jwtKey = utils.GenerateRandomBytes(32)

func Auth(next echo.HandlerFunc) echo.HandlerFunc {
	return func(c echo.Context) error {
		token, err := c.Cookie("token")
		if err != nil || token.Value == "" {
			log.WithFields(log.Fields{
				"location": "auth-handler",
				"error":    err,
			}).Errorln("Can't get token from cookie")
			return c.JSON(http.StatusInternalServerError, utils.Response{
				Message: err.Error(),
			})
		}
		tknStr := token.Value

		claims := &model.Claims{}

		tkn, err := jwt.ParseWithClaims(tknStr, claims, func(token *jwt.Token) (interface{}, error) {
			return jwtKey, nil
		})
		if err != nil {
			if err == jwt.ErrSignatureInvalid {
				return c.JSON(http.StatusUnauthorized, utils.Response{
					Message: "Invalid token",
				})
			}
		}
		log.WithFields(log.Fields{
			"location": "auth-handler",
			"token":    tkn,
			"claims": claims,
		}).Infoln("JWT Token")
		
		c.Set("username", claims.Username)
		
		return next(c)
	}
}
