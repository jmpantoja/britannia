#
# Creamos el droplet

variable "github_user" {}
variable "github_password" {}
variable "github_repository_url" {}
variable "github_branch" {}
variable "app_url" {}
variable "app_secret" {}
variable "app_env" {}
variable "mysql_root_password" {}
variable "mysql_database" {}
variable "mysql_user" {}
variable "mysql_password" {}

variable "app_descom_user" {}
variable "app_descom_password" {}

variable "app_mailer_managers" {}
variable "app_mailer_reception" {}
variable "app_mailer_teachers" {}

data "template_file" "init" {
template = "${file("userdata.yaml.tpl")}"
vars = {
    github_repository_url = "${var.github_repository_url}"
    github_branch = "${var.github_branch}"

    app_url = "${var.app_url}"
    app_secret = "${var.app_secret}"
    app_env = "${var.app_env}"

    mysql_root_password = "${var.mysql_root_password}"
    mysql_database = "${var.mysql_database}"
    mysql_user = "${var.mysql_user}"
    mysql_password = "${var.mysql_password}"

    app_descom_user = "${var.app_descom_user}"
    app_descom_password = "${var.app_descom_password}"

    app_mailer_managers = "${var.app_mailer_managers}"
    app_mailer_reception = "${var.app_mailer_reception}"
    app_mailer_teachers = "${var.app_mailer_teachers}"
  }
}

resource "digitalocean_droplet" "web" {
  image     = "ubuntu-18-04-x64"
  name      = "britannia"
  region    = "lon1"
  size      = "s-1vcpu-1gb"
  user_data = "${data.template_file.init.rendered}"
  ssh_keys  = ["${digitalocean_ssh_key.britannia.fingerprint}"]

  provisioner "remote-exec" {
    inline = [
      "mkdir /root/tmp/dumps/",
      "mkdir -p /root/tmp/uploads/attachments",
      "mkdir -p /root/tmp/uploads/photos",
    ]
  }

  provisioner "file" {
    source      = "../api/dumps/"
    destination = "/root/tmp/dumps/"
  }

  provisioner "file" {
    source      = "../api/uploads/attachments/"
    destination = "/root/tmp/uploads/attachments/"
  }

   provisioner "file" {
     source      = "../api/uploads/photos/"
     destination = "/root/tmp/uploads/photos/"
   }
}
