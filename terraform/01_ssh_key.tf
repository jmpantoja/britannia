#
# Exportamos nuestra key SSH

resource "digitalocean_ssh_key" "britannia" {
  name       = "britannia"
  public_key = "${file("id_rsa.pub")}"
}

