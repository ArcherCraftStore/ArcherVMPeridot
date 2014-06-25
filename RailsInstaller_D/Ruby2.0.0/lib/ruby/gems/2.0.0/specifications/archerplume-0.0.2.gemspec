# -*- encoding: utf-8 -*-

Gem::Specification.new do |s|
  s.name = "archerplume"
  s.version = "0.0.2"

  s.required_rubygems_version = Gem::Requirement.new(">= 0") if s.respond_to? :required_rubygems_version=
  s.authors = ["Weldon"]
  s.date = "2014-06-19"
  s.description = "ArcherPlume Blog FrameWork"
  s.email = ["weldon23.henson23@gmail.com\t"]
  s.executables = ["bundle", "rails", "rake"]
  s.files = ["bin/bundle", "bin/rails", "bin/rake"]
  s.homepage = "http://archercraftstore.github.io"
  s.licenses = ["MIT"]
  s.require_paths = ["lib"]
  s.rubygems_version = "2.0.2"
  s.summary = "ABF"

  if s.respond_to? :specification_version then
    s.specification_version = 4

    if Gem::Version.new(Gem::VERSION) >= Gem::Version.new('1.2.0') then
      s.add_development_dependency(%q<bundler>, ["~> 1.3"])
      s.add_development_dependency(%q<rake>, [">= 0"])
      s.add_runtime_dependency(%q<rspec>, [">= 0"])
    else
      s.add_dependency(%q<bundler>, ["~> 1.3"])
      s.add_dependency(%q<rake>, [">= 0"])
      s.add_dependency(%q<rspec>, [">= 0"])
    end
  else
    s.add_dependency(%q<bundler>, ["~> 1.3"])
    s.add_dependency(%q<rake>, [">= 0"])
    s.add_dependency(%q<rspec>, [">= 0"])
  end
end
