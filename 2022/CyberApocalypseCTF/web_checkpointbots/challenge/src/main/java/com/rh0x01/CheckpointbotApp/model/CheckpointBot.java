package com.rh0x01.CheckpointbotApp.model;

import java.io.ByteArrayInputStream;
import java.io.ByteArrayOutputStream;
import java.io.IOException;
import java.io.ObjectInputStream;
import java.io.ObjectOutputStream;

import javax.persistence.Column;
import javax.persistence.Entity;
import javax.persistence.GeneratedValue;
import javax.persistence.GenerationType;
import javax.persistence.Id;
import javax.persistence.Lob;
import javax.persistence.Transient;

import com.rh0x01.CheckpointbotApp.utils.CheckInUtility;

@Entity
public class CheckpointBot {
    @Id
    @GeneratedValue(strategy=GenerationType.AUTO)
    private Integer id;
    private String token;
    @Lob
    @Column(name="byte_object", length=100000)
    private byte[] byteObject;
    @Transient
    private CheckInUtility checkInUtility;

    @Transient
    public CheckInUtility getCheckInUtility() {
        ByteArrayInputStream bais;
        ObjectInputStream in ;
        try {
            bais = new ByteArrayInputStream(byteObject); in = new ObjectInputStream(bais);
            checkInUtility = (CheckInUtility) in .readObject(); in .close();
        } catch (IOException ex) {
            ex.printStackTrace();
        } catch (ClassNotFoundException ex) {
            ex.printStackTrace();
        }
        return checkInUtility;
    }

    public void setCheckInUtility(CheckInUtility checkInUtility) {
        this.checkInUtility = checkInUtility;
        ByteArrayOutputStream baos;
        ObjectOutputStream out;
        baos = new ByteArrayOutputStream();
        try {
            out = new ObjectOutputStream(baos);
            out.writeObject(checkInUtility);
            out.close();
        } catch (IOException e) {
            e.printStackTrace();
        }
        this.byteObject = baos.toByteArray();
    }

    public byte[] getByteObject() {
        return byteObject;
    }

    public void setByteObject(byte[] byteObject) {
        ByteArrayInputStream bais;
        ObjectInputStream in ;
        try {
            bais = new ByteArrayInputStream(byteObject); in = new ObjectInputStream(bais);
            checkInUtility = (CheckInUtility) in .readObject(); in .close();
        } catch (IOException ex) {
            ex.printStackTrace();
        } catch (ClassNotFoundException ex) {
            ex.printStackTrace();
        }
        this.byteObject = byteObject;
    }

    public Integer getId() {
        return id;
    }

    public void setId(Integer id) {
        this.id = id;
    }

    public String getToken() {
        return token;
    }

    public void setToken(String token) {
        this.token = token;
    }

}