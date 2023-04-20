{ pkgs ? import <nixpkgs> {} }:
let
  phpWithExtensions = pkgs.php81.withExtensions ({ all, ... }: with all; [
    imagick
  ]);
in
pkgs.mkShell {
  buildInputs = [
    phpWithExtensions
  ];
}
