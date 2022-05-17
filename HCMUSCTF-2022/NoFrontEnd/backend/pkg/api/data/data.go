package data

import (
	"encoding/json"
	"net/http"

	"hcmus.ctf/challenge/model"
	"hcmus.ctf/challenge/utils"
	"github.com/labstack/echo/v4"
	log "github.com/sirupsen/logrus"
)

func (h *Handler) InsertData(c echo.Context) error {
	var userData model.UserPostData
	err := c.Bind(&userData)
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
	if userData.Key == "" || userData.Data == "" {
		return c.JSON(http.StatusUnprocessableEntity, utils.Response{
			Message: "Cannot insert, wrong data",
		})
	}

	username, ok := c.Get("username").(string)

	if !ok {
		return c.JSON(http.StatusUnauthorized, utils.Response{
			Message: "Claims not found",
		})
	}
	added, err := h.DB.InsertData(username, userData)
	if err != nil {
		log.WithFields(
			log.Fields{
				"error":    err.Error(),
				"location": "get-data-handler",
			},
		).Errorln("error getting data from db")
		return c.JSON(http.StatusUnprocessableEntity, utils.Response{
			Message: err.Error(),
		})
	}
	
	if !added {
		return c.JSON(http.StatusUnauthorized, utils.Response{
			Message: "Key is existed",
		})
	}

	byteData, err := json.Marshal(username+userData.Key)
	if err != nil {
		return c.JSON(http.StatusUnprocessableEntity, utils.Response{
			Message: "Cannot marshal data",
		})
	}

	signature := utils.CreateSignature(byteData)

	return c.JSON(http.StatusOK, utils.Response{
		Data:    signature,
		Message: "Insert data successful",
	})
}

func (h *Handler) GetData(c echo.Context) error {
	var userData model.UserPostData
	err := c.Bind(&userData)
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
	if userData.Key == "" {
		return c.JSON(http.StatusUnprocessableEntity, utils.Response{
			Message: "Cannot insert, wrong data",
		})
	}

	username, ok := c.Get("username").(string)

	if !ok {
		return c.JSON(http.StatusUnauthorized, utils.Response{
			Message: "Claims not found",
		})
	}

	byteData, err := json.Marshal(username+userData.Key)
	if err != nil {
		return c.JSON(http.StatusUnprocessableEntity, utils.Response{
			Message: "Cannot marshal data",
		})
	}

	valid := utils.ValidationSignature(byteData, c.Request().Header.Get("Signature"))
	if !valid {
		return c.JSON(http.StatusUnprocessableEntity, utils.Response{
			Message: "Invalid signature",
		})
	}

	data, err := h.DB.GetData(username, userData)
	if err != nil {
		log.WithFields(
			log.Fields{
				"error":    err.Error(),
				"location": "get-data-handler",
			},
		).Errorln("error getting data from db")
		return c.JSON(http.StatusUnprocessableEntity, utils.Response{
			Message: "Cannot get data",
		})
	}
	return c.JSON(http.StatusOK, utils.Response{
		Data:    data,
		Message: "Get data successful",
	})
}
