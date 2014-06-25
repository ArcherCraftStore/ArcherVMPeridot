# coding: utf-8
lib = File.expand_path('../lib', __FILE__)
$LOAD_PATH.unshift(lib) unless $LOAD_PATH.include?(lib)
require 'archerplume/version'

Gem::Specification.new do |spec|
  spec.name          = "archerplume"
  spec.version       = Archerplume::VERSION
  spec.authors       = ["Weldon"]
  spec.email         = ["weldon23.henson23@gmail.com\t"]
  spec.description   = "ArcherPlume Blog FrameWork"
  spec.summary       = "ABF"
  spec.homepage      = "http://archercraftstore.github.io"
  spec.license       = "MIT"

  spec.files         = `git ls-files`.split($/)
  spec.executables   = spec.files.grep(%r{^bin/}) { |f| File.basename(f) }
  spec.test_files    = spec.files.grep(%r{^(test|spec|features)/})
  spec.require_paths = ["lib"]

  spec.add_development_dependency "bundler", "~> 1.3"
  spec.add_development_dependency "rake"
  spec.add_runtime_dependency "rspec"
end
