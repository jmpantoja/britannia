#
# Creamos el droplet

variable "github_user" {}
variable "github_password" {}
variable "github_repository_url" {}
variable "github_branch" {}
variable "app_url" {}
variable "app_secret" {}
variable "mysql_root_password" {}
variable "mysql_database" {}
variable "mysql_user" {}
variable "mysql_password" {}

data "template_file" "init" {
template = "${file("userdata.yaml.tpl")}"
vars = {
    github_repository_url = "${var.github_repository_url}"
    github_branch = "${var.github_branch}"
    app_url = "${var.app_url}"
    app_secret = "${var.app_secret}"
    mysql_root_password = "${var.mysql_root_password}"
    mysql_database = "${var.mysql_database}"
    mysql_user = "${var.mysql_user}"
    mysql_password = "${var.mysql_password}"
  }
}

resource "digitalocean_droplet" "web" {
  image     = "ubuntu-18-04-x64"
  name      = "britannia"
  region    = "lon1"
  size      = "s-1vcpu-1gb"
  user_data = "${data.template_file.init.rendered}"
  ssh_keys  = ["${digitalocean_ssh_key.britannia.fingerprint}"]
}
